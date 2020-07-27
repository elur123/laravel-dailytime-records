import routes from './routes';
import VueRouter from 'vue-router';
import Swal from 'sweetalert2'
import { Icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'vue-loading-overlay/dist/vue-loading.css';

require('./bootstrap');


window.Swal = Swal;
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
})

window.toast = toast;

window.Vue = require('vue');
Vue.use(VueRouter);
const router = new VueRouter({
    mode: 'history',
    routes
});

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});
//laravel-vue-pagination
Vue.component('pagination', require('laravel-vue-pagination'))

new Vue({
    router,
    // render: h => h(App)
}).$mount('#app');
