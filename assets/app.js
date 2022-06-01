/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap/dist/js/bootstrap.bundle';
import 'bootstrap/dist/css/bootstrap.css';
import 'vue-toastification/dist/index.css';
import feather from 'feather-icons/dist/feather.js';

import { createApp } from 'vue'

// start the Stimulus application
import './bootstrap';
import AppDashboard from './pages/app-dashboard'
import AppRegister from './pages/app-register'
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
const app = createApp({
})
app.component('AppDashboard', AppDashboard)
app.component('AppRegister', AppRegister)
app.mount('#app');