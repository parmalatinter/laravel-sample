import VueRouter from 'vue-router';
let routes=[
    {
        path: '/',
        // component: require('./Components/Home.vue')
    },
    {
        path: '/datatables',
        component: require('../components/TableComponent').default
    }
];

export default new VueRouter({routes});
