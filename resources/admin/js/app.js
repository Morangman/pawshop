import components from './components';
import DeleteConfirmation from '../../common/js/components/delete';
import Swatches from 'vue-swatches';
import i18n from '../../common/js/utils/i18n';
import Datetime from 'vue-datetime';
import 'vue-datetime/dist/vue-datetime.css';
import Multiselect from 'vue-multiselect';
import 'vue-search-select/dist/VueSearchSelect.css';
import { Vue } from '../../common/js/main';

require('babel-polyfill');

require('brace/theme/chrome');

require('brace/mode/javascript');

try {
    window.Popper = require('popper.js').default;

    require('bootstrap');
} catch (e) {}

Vue.component('delete-confirmation', DeleteConfirmation);

Vue.component('v-swatches', Swatches);

Vue.component('multiselect', Multiselect);

Vue.use(Datetime);

export const eventBus = new Vue();

new Vue({
    el: '#app',
    i18n,
    components,
});

Object.defineProperty(Vue.prototype, '$_', { value: window._ });

console.log("Develop by Sinepolsky Dmitriy");
console.log("Contact telegram: @dsdvnd7");

