window._ = require('lodash');
window.$ = require( "jquery" );
window.THBText = require('thai-baht-text');
window.moment = require('moment');
window.qs = require('qs');
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

window.Form =  require('./form');

import Swal from 'sweetalert2/src/sweetalert2.js'

window.Swal = Swal;

Vue.component('sides', require('./vue/components/Sides.vue').default);
Vue.component('side', require('./vue/components/Side.vue').default);
Vue.component('tab', require('./vue/components/Tab.vue').default);
Vue.component('tabs', require('./vue/components/Tabs.vue').default);
Vue.component('loader', require('./vue/components/Loader.vue').default);
Vue.component('loading', require('./vue/components/Loading.vue').default);
Vue.component('search-bar', require('./vue/components/SeachBar.vue').default);
Vue.component('breadcrumb', require('./vue/components/Breadcrumb').default);
Vue.component('topbutton', require('./vue/components/TopButton').default);
Vue.component('card', require('./vue/components/Card').default);
Vue.component('cardheader', require('./vue/components/CardHeader').default);
Vue.component('cardbody', require('./vue/components/CardBody').default);
Vue.component('pill', require('./vue/components/Pill').default);
Vue.component('base-button', require('./vue/components/BaseButton').default);
Vue.component('modal', require('./vue/components/Modal').default);
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
