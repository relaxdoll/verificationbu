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
// window.Vue = require('vue');

import LineChart from './components/chart/LineChart';
import * as chartConfigs from './components/chart/config';

window.LineChart = LineChart;
window.chartConfigs = chartConfigs;

require('../vuelidate.js');
// Vue.component('inputbox', require('./components/Inputbox.vue').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('drawer', require('./components/Drawer.vue').default);
Vue.component('tab', require('./components/Tab.vue').default);
Vue.component('tabs', require('./components/Tabs.vue').default);

Vue.component('gear', require('./components/Gear').default);
Vue.component('darktablei', require('./components/TableIndependent').default);

Vue.component('driverdetail', require('./components/DriverDetail').default);
Vue.component('darktable', require('./components/Table').default);
Vue.component('vehicle-type', require('./components/settings/vehicle/VehicleType').default);
Vue.component('vehicle-type-create', require('./components/settings/vehicle/VehicleTypeCreate').default);

Vue.component('payment-type', require('./components/settings/vehicle/PaymentType').default);
Vue.component('payment-type-create', require('./components/settings/vehicle/PaymentTypeCreate').default);

Vue.component('pill-input', require('./components/PillInput.vue').default);
Vue.component('v-form', require('./components/VForm').default);
Vue.component('select-box', require('./components/SelectBox').default);
Vue.component('base-input', require('./components/BaseInput.vue').default);
