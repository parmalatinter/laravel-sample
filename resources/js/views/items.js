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
                    data: "id",
                    visible: false,
                },
                {
                    data: "name",
                },
                {
                    data: null,
                    "render": function ( data, type, full, meta ) {
                        let $td = $('#items-table > tbody  td:nth-child(2)').eq(0).clone();
                        let btnClassList = ['.showBtn', '.editBtn'];
                        let formClassList = ['.deleteForm'];
                        for (const index in btnClassList) {
                            let btnClassName = btnClassList[index];
                            let $btn = $td.find(btnClassName);
                            let href = $btn.attr('href').replace('#id', data.id);
                            $btn.attr({
                                href : href
                            });
                        }
                        for (const index in formClassList) {
                            let formClassName = formClassList[index];
                            let $form = $td.find(formClassName);
                            let action = $form.attr('action').replace('#id', data.id);
                            $form.attr({
                                href : action
                            });
                        }
                        return $td.html();
                    }
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
