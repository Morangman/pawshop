<template>
    <div class="header-search">
        <div class="header-search-form">
            <input v-on:keyup="searchDevice" v-model="name" type="text" placeholder="Write text">
            <a href="javascript:void(0)"></a>
        </div>
        <div class="header-search-popup" v-if="searchDevices.length">
            <ul class="header-search-popup-list" id="header-search-popup-list">
                <li v-for="(device, index) in searchDevices" :key="`device_${index}`" style="margin-bottom: 10px;">
                    <a :href="$r('get-category', { category: device.id })" class="link">
                        <span class="name">{{ device.name }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="header-search-toggle"><img src="../../client/images/icon_search.svg" alt=""></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            searchDevices: {},
        };
    },

    methods: {
        searchDevice() {
            this.searchDevices = {};

            axios.get(
                Router.route('header-search', { name: this.name }),
            ).then((data) => {
                this.searchDevices = data.data;
            }).catch(({ response: { data: { errors } } }) => {
                console.log(errors);
            });
        }
    }
}
</script>
