
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import MainApp from './MainApp';
import router from './router';
import {store} from './Store/store';
import swal from 'sweetalert2';
import axios from 'axios'

axios.defaults.withCredentials;
require('./bootstrap');
window.swal = swal;
const app = new Vue({
    el: '#app',
    store,
    router,
    axios,
    components: {
        MainApp,
    },

});

