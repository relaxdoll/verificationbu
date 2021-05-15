import VueKonva from "vue-konva";

require('../bootstrap.js');

import Vuex from 'vuex'
import storeData from "../store/store"
import {mapState} from 'vuex'
import { mapFields } from 'vuex-map-fields';

window.mapState = mapState;
window.mapFields = mapFields;

Vue.use(Vuex);
window.store = new Vuex.Store(
    storeData
);

require('../vuelidate.js');
Vue.use(VueKonva);

import DatePicker from 'v-calendar/lib/components/date-picker.umd'

// CSS
Vue.component('konva', require('./components/Konva.vue').default);

Vue.component('date-picker', DatePicker);
Vue.component('select-box', require('./components/SelectBox').default);
Vue.component('base-input', require('./components/BaseInput.vue').default);
Vue.component('base-datepicker', require('./components/BaseDatepicker').default);
Vue.component('base-radio-input', require('./components/BaseRadioInput').default);
Vue.component('pill-input', require('./components/PillInput.vue').default);
// Vue.component('inputbox', require('./components/Inputbox').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('drawer', require('./components/Drawer.vue').default);
Vue.component('tab', require('./components/Tab.vue').default);
Vue.component('tabs', require('./components/Tabs.vue').default);
Vue.component('wizard', require('./components/Wizard').default);
Vue.component('wizard-tab', require('./components/WizardTab').default);
Vue.component('v-form', require('./components/VForm').default);
Vue.component('image-upload', require('./components/ImageUpload').default);
Vue.component('liffloader', require('../vux/components/loading/index.vue').default);

Vue.component('vehicle-type-create', require('./components/settings/vehicle/VehicleTypeCreate').default);
