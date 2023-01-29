$(document).ready(function () {
    $('#products-table').DataTable();
});

import { createApp } from 'vue/dist/vue.esm-bundler.js';
window.Vue = createApp();

window.Vue.component('example-component', require('../components/ExampleComponent.vue').default);

window.Vue.mount('#app');
