import VueRouter from 'vue-router';
let routes=[
    {
        path: '/',
        component: require('../components/HomeComponent').default
    },
    {
        path: '/account',
        component: require('../components/AccountComponent').default
    },
    {
        path: '/blogs',
        component: require('../components/BlogsComponent').default
    },
    {
        path: '/datatables',
        component: require('../components/TableComponent').default
    }
];

export default new VueRouter({routes});
