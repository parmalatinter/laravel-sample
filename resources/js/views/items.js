$(document).ready(function () {
    $('#items-table').DataTable(
        {
            processing: true,
            serverSide: true,
            ajax: {
                url :'api/items',
                dataSrc: 'data',
            },
            "columns": [
                {
                    data: "name"
                },
                {
                    data: null,
                    defaultContent: '<button>Click!</button>',
                },
            ],
            deferLoading: 57,

        }
    );
});

import { createApp } from 'vue/dist/vue.esm-bundler.js';
window.Vue = createApp();

window.Vue.component('example-component', require('../components/ExampleComponent.vue').default);

window.Vue.mount('#app');
