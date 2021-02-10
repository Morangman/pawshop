require('./lodash');

window.$ = window.jQuery = require('jquery');

require('./utils/notify');
require('./utils/confirmation');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

import Vue from 'vue';
import extendVue from './extensions/vue';
import BootstrapVue from 'bootstrap-vue/esm/index';
import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import Spinner from './components/spinner';
import { Settings } from 'luxon';

extendVue(Vue);

Vue.component('vue-select', VueSelect);
Vue.component('spinner', Spinner);

Vue.use(BootstrapVue);

Settings.defaultLocale = 'uk';

export { Vue };
