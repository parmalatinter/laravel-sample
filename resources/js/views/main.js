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

