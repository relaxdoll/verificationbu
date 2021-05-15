require('../../bootstrap.js');

import Vuex from 'vuex'
import storeData from "../../store/store"
import {mapState} from 'vuex'
import { mapFields } from 'vuex-map-fields';

window.mapState = mapState;
window.mapFields = mapFields;

Vue.use(Vuex);
window.store = new Vuex.Store(
    storeData
);

require('../../vuelidate.js');

import DatePicker from 'v-calendar/lib/components/date-picker.umd'

Vue.component('date-picker', DatePicker);
Vue.component('select-box', require('../components/SelectBox').default);
Vue.component('base-input', require('../components/BaseInput.vue').default);
Vue.component('base-datepicker', require('../components/BaseDatepicker').default);

Vue.component('loader', require('../components/Loader.vue').default);
Vue.component('drawer', require('../components/Drawer.vue').default);
Vue.component('tab', require('../components/Tab.vue').default);
Vue.component('tabs', require('../components/Tabs.vue').default);


Vue.component('v-form', require('../components/VForm').default);

Vue.component('gear', require('../components/Gear').default);
Vue.component('darkgrouptable', require('../components/TableGroup').default);

Vue.component('differential', require('../components/vehicle_frames/Differential').default);
Vue.component('wheel', require('../components/vehicle_frames/Wheel').default);
Vue.component('trailer-bar', require('../components/vehicle_frames/TrailerBar').default);
Vue.component('trailer-sub-bar', require('../components/vehicle_frames/TrailerSubBar').default);
Vue.component('trailer-upper-body', require('../components/vehicle_frames/TrailerUpperBody').default);

Vue.component('trailer-head', require('../components/vehicle_frames/TrailerHead').default);
Vue.component('trailer-8', require('../components/vehicle_frames/VehicleTrailer8').default);
Vue.component('trailer-12', require('../components/vehicle_frames/VehicleTrailer12').default);
Vue.component('vehicle-10', require('../components/vehicle_frames/Vehicle10').default);
Vue.component('vehicle-tail', require('../components/vehicle_frames/VehicleTail').default);
Vue.component('vehicle-head', require('../components/vehicle_frames/VehicleHead').default);
