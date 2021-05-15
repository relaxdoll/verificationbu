require('../bootstrap.js');

import Vuex from 'vuex'
import storeData from "../store/liff_example"

import {mapState} from 'vuex'
import { mapFields } from 'vuex-map-fields';

window.mapState = mapState;
window.mapFields = mapFields;

Vue.use(Vuex);
window.store = new Vuex.Store(
    storeData
);

require('../vuelidate.js');

import DatePicker from 'v-calendar/lib/components/date-picker.umd'

Vue.component('date-picker', DatePicker);
Vue.component('cardselection', require('./components/CardSelection').default);
Vue.component('cardinput', require('./components/CardInput').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('liffdrawer', require('./components/LiffDrawer.vue').default);
Vue.component('liffdrawer2', require('./components/LiffDrawer2.vue').default);
Vue.component('liffdrawerspace', require('./components/LiffDrawerSpace').default);
Vue.component('liffdropdown', require('./components/LiffDropdown.vue').default);
Vue.component('liffdropdown2', require('./components/LiffDropdown2.vue').default);
Vue.component('liffdropdownspace', require('./components/LiffDropdownSpace').default);
Vue.component('liffinput', require('./components/LiffInput.vue').default);
Vue.component('liff-input', require('./components/LiffInput2.vue').default);
Vue.component('lifftopinput', require('./components/LiffTopInput').default);
Vue.component('liffloader', require('../vux/components/loading/index.vue').default);
Vue.component('liff-uploader', require('../vux/components/uploading/index.vue').default);
Vue.component('liffalert', require('../vux/components/alert/index.vue').default);
Vue.component('liffgroup', require('./components/LiffGroup').default);
Vue.component('liff-input-group', require('./components/LiffInputGroup').default);
Vue.component('liffoption', require('./components/LiffOption').default);
Vue.component('selector', require('./components/Selector.vue').default);
Vue.component('selector-icon', require('./components/SelectorIcon').default);
Vue.component('imagecrop', require('./components/ImageCrop').default);
Vue.component('liffbottombutton', require('./components/LiffBottomButton').default);
Vue.component('liff-form', require('./components/LiffForm').default);
Vue.component('liff-image-upload', require('./components/LiffImageUpload').default);
Vue.component('liff-header', require('./components/LiffHeader').default);
Vue.component('liff-submit-button', require('./components/LiffSubmitButton').default);
Vue.component('liff-radial-progress-bar', require('./components/LiffRadialProgressBar').default);

Vue.component('liff-wheel', require('./components/LiffWheel').default);
Vue.component('liff-differential', require('./components/LiffDifferential').default);
Vue.component('liff-trailer-bar', require('./components/LiffTrailerBar').default);
Vue.component('liff-trailer-upper-body', require('./components/LiffTrailerUpperBody').default);
Vue.component('liff-trailer-sub-bar', require('./components/LiffTrailerSubBar').default);
Vue.component('liff-vehicle-trailer-8', require('./components/LiffVehicleTrailer8').default);
Vue.component('liff-vehicle-trailer-12', require('./components/LiffVehicleTrailer12').default);
Vue.component('liff-vehicle-head', require('./components/LiffVehicleHead').default);
Vue.component('liff-vehicle-tail', require('./components/LiffVehicleTail').default);
Vue.component('liff-vehicle-10', require('./components/LiffVehicle10').default);
Vue.component('liff-vehicle-trailer-head', require('./components/LiffVehicleTrailerHead').default);
Vue.component('liff-datepicker', require('./components/LiffDatePicker').default);
Vue.component('liff-tire-change-request', require('./components/tires/TireChangeRequest').default);
Vue.component('liff-tire-pressure-and-tread', require('./components/tires/TirePressureAndTread').default);
Vue.component('liff-maintenance-approval-create', require('./components/maintenances/approval/MaintenanceApprovalCreate').default);
Vue.component('liff-maintenance-approval-my-request', require('./components/maintenances/approval/MaintenanceApprovalMyRequest').default);
Vue.component('liff-maintenance-approval-request-list', require('./components/maintenances/approval/LiffRequestList').default);
Vue.component('liff-maintenance-approval-update-list', require('./components/maintenances/approval/LiffUpdateList').default);
