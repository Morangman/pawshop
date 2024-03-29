/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import { Vue } from '../common/js/main';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('search-header', require('./components/SearchHeader.vue').default);

Vue.component('search', require('./components/Search.vue').default);

Vue.component('sell-device', require('./components/SellDevice.vue').default);

Vue.component('cart', require('./components/Cart.vue').default);

Vue.component('account', require('./components/Account.vue').default);

Vue.component('header-cart', require('./components/HeaderCart.vue').default);

Vue.component('checkout', require('./components/Checkout.vue').default);

Vue.component('thanks', require('./components/Thanks.vue').default);

Vue.component('breadcrumbs', require('./components/Breadcrumbs.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
