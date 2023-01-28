require('./bootstrap');
require('admin-lte');
require('jquery');
require('datatables.net-bs5');
require('datatables.net-buttons-bs5');

import { createApp } from 'vue/dist/vue.esm-bundler.js';
window.Vue = createApp();

window.Vue.component('example-component', require('./components/ExampleComponent.vue').default);

window.Vue.mount('#app');
