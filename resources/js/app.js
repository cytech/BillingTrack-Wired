/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import '../sass/app.scss'
// SortableJS https://github.com/SortableJS/Sortable
import Sortable from 'sortablejs/Sortable.min';

window.Sortable = Sortable;
// autosize https://github.com/jackmoore/autosize
import autosize from "autosize";

window.autosize = autosize;
// fullcalendar https://github.com/fullcalendar/fullcalendar
import {Calendar} from '@fullcalendar/core';

window.Calendar = Calendar;
import interactionPlugin from '@fullcalendar/interaction';

window.interactionPlugin = interactionPlugin;
import dayGridPlugin from '@fullcalendar/daygrid';

window.dayGridPlugin = dayGridPlugin;
import timeGridPlugin from '@fullcalendar/timegrid';

window.timeGridPlugin = timeGridPlugin;
import listPlugin from '@fullcalendar/list';

window.listPlugin = listPlugin;
import bootstrap5Plugin from '@fullcalendar/bootstrap5'

window.bootstrap5Plugin = bootstrap5Plugin

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
