<template>
    <div class="order-search-outer">
        <h5>Search the device:</h5>
        <div class="order-search">
            <div class="order-search-form">
                <input v-on:keyup="searchDevice" v-model="name" type="text" placeholder="Write text for search">
                <a href="javascript:void(0)" v-on:click="searchDevice" class="btn red-btn">Search</a>
            </div>
            <div class="order-search-popup" v-if="searchDevices.length">
                <ul class="order-search-popup-list">
                    <li v-for="(device, index) in searchDevices" :key="`device_${index}`">
                        <a :href="$r('get-category', { slug: device.slug })" class="link">
                            <div class="image"><img :src="device.image" :alt="device.name"></div>
                            <span class="name">{{ device.name }}</span>
                        </a>
                        <div class="price">up to <strong>{{ device.custom_text }}</strong></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                searchDevices: {},
                name: null,
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
            },
        }
    };
</script>
