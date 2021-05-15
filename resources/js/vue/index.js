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
import VueHtmlToPaper from 'vue-html-to-paper';

Vue.use(VueHtmlToPaper);

window.LineChart = LineChart;
window.chartConfigs = chartConfigs;

require('../vuelidate.js');
import google from './components/chart/LineChart';
import DatePicker from 'v-calendar/lib/components/date-picker.umd'

// Vue.component('inputbox', require('./components/Inputbox.vue').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('drawer', require('./components/Drawer.vue').default);
Vue.component('tab', require('./components/Tab.vue').default);
Vue.component('tabs', require('./components/Tabs.vue').default);
Vue.component('panel', require('./components/Panel').default);
Vue.component('panels', require('./components/Panels').default);

Vue.component('v-form', require('./components/VForm').default);
Vue.component('date-picker', DatePicker);
Vue.component('base-image', require('./components/BaseImage').default);
Vue.component('base-input', require('./components/BaseInput.vue').default);
Vue.component('base-datepicker', require('./components/BaseDatepicker').default);
Vue.component('base-daterangepicker', require('./components/BaseDateRangepicker').default);
Vue.component('select-box', require('./components/SelectBox').default);
Vue.component('pill-input', require('./components/PillInput.vue').default);

Vue.component('gear', require('./components/Gear').default);
Vue.component('darktable', require('./components/Table').default);
Vue.component('darkgrouptable', require('./components/TableGroup').default);
Vue.component('darktablei', require('./components/TableIndependent').default);

Vue.component('driverdetail', require('./components/DriverDetail').default);
Vue.component('vehicledetail', require('./components/vehicle/VehicleDetail').default);
Vue.component('refueldetail', require('./components/RefuelDetail').default);
Vue.component('whtax', require('./components/zoho/WHTax').default);
