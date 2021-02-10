import Vue from 'vue';
import VueInternationalization from 'vue-i18n';
import messages from '../generated/i18n-messages';

Vue.use(VueInternationalization);

const locale = document.documentElement.lang.substr(0, 2);

export default new VueInternationalization({ locale, messages });
