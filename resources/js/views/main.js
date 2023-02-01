// import { createApp } from 'vue/dist/vue.esm-bundler.js';
//
// import 'vuetify/styles'
// import { createVuetify } from 'vuetify'
// import Vue from 'vue'
// import VueRouter from 'vue-router'
//
// Vue.use(VueRouter)
//
// import "vuetify/dist/vuetify.min.css";
// import '@mdi/font/css/materialdesignicons.css' // この行を追加
//
// import * as components from 'vuetify/components'
// import * as directives from 'vuetify/directives'
//
// const vuetify = createVuetify({
//     components,
//     directives,
// })
//
//
// window.Vue = createApp()
//     .use(vuetify)
//     .use(router);
//
// window.Vue
//     .component('app-component', require('../components/AppComponent.vue').default);
//
// window.Vue.mount('#app');

import Vue from 'vue'
import Vuetify from 'vuetify' // path to vuetify export
import router from '../plugins/router.js'
import VueRouter from 'vue-router'
import "vuetify/dist/vuetify.min.css";
import '@mdi/font/css/materialdesignicons.css' // この行を追加

Vue
    .use(Vuetify)
    .use(VueRouter)

new Vue({
    el: '#app',
    vuetify:new Vuetify(),
    router: router,
    render: h => h(require('../components/AppComponent.vue').default)
});
