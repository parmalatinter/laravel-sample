import VueRouter from 'vue-router';
let routes=[
    {
        path: '/',
        component: require('../components/HomeComponent').default
    },
    {
        path: '/datatables',
        component: require('../components/TableComponent').default
    },
    {
        path: '/account',
        component: require('../components/AccountComponent').default
    }
];

export default new VueRouter({routes});
