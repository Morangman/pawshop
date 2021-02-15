<template>
<div>
    <h1>Start Selling</h1>
    <div class="description">Find the product you'd like to trade-in for cash</div>
    <ul class="order-steps-list">
        <li class="active-step">
            <a href=""><i>1</i> <span>Device</span></a>
        </li>
        <li>
            <a href=""><i>2</i> <span>Model</span></a>
        </li>
        <li>
            <a href=""><i>3</i> <span>Condition</span></a>
        </li>
        <li>
            <a href=""><i>4</i> <span>Carrier</span></a>
        </li>
        <li>
            <a href=""><i>5</i> <span>Storage capacity</span></a>
        </li>
        <li>
            <a href=""><i>6</i> <span>Finish </span></a>
        </li>
    </ul>

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
                        <a :href="$r('get-category', { category: device.id })" class="link">
                            <div class="image"><img :src="device.image" alt=""></div>
                            <span class="name">{{ device.name }}</span>
                        </a>
                        <div class="price">up to <strong>{{ device.custom_text }}</strong></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="order-content">
        <h4>Or choose the device for sell:</h4>
        <ul class="order-list">
            <li v-for="(category, index) in categories" :key="`device_${index}`">
                <a :href="$r('get-category', { category: category.id })">
                    <div class="image"><img :src="category.image" alt="" /></div>
                    <h5>{{ category.name }}</h5>
                    <div v-if="category.custom_text" class="price">Cash in up to ${{ category.custom_text }}</div>
                </a>
            </li>
        </ul>
    </div>

    <div class="faqs-content" v-if="!faqsIsset">
        <h2>FAQs</h2>
        <div class="faqs-block" v-for="(faq, index) in faqs.data" :key="`faq_${index}`">
            <div class="faqs-question">{{ faq.title }}</div>
            <div class="faqs-answer" v-html="faq.text"></div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: {
            categories: {
                type: Array,
                required: false,
            },
            faqs: {
                type: Object,
                required: false,
            },
        },

        data() {
            return {
                name: '',
                searchDevices: {},
                faqsIsset: false
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
        },

        created(){
            this.faqsIsset = _.isEmpty(this.faqs);
        }
    }
</script>
