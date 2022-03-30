
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import 'jquery-ui/ui/widgets/autocomplete';
// import 'jquery-ui/ui/widgets/dialog';
// import 'jquery-ui/ui/widgets/sortable';
//datatables.net
// import 'datatables.net/js/jquery.dataTables.min';
// import 'datatables.net-bs4/js/dataTables.bootstrap4.min';
// Default SortableJS
import Sortable from 'sortablejs/Sortable.min';
window.Sortable = Sortable;
// autosize https://github.com/jackmoore/autosize
import autosize from "autosize";
window.autosize = autosize;

//alpinejs
// import Alpine from 'alpinejs'
// window.Alpine = Alpine
// Alpine.start()

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// Vue.config.productionTip = false;

// const app = new Vue({
//     el: '#app',
// });
