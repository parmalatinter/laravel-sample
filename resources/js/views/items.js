// https://www.binaryboxtuts.com/php-tutorials/laravel-tutorials/laravel-8-crud-using-ajax-with-datatables-tutorial/
$(document).ready(function () {
    let $table = $('#items-table');
    $table.DataTable(
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
                        return $td.html();
                    }
                },
            ],
            deferLoading: 57,

        }
    );
});

function reloadTable()
{
    /*
        reload the data on the datatable
    */
    $table.DataTable().ajax.reload();
}

/*
    check if form submitted is for creating or updating
*/
$("#saveBtn").click(function(event ){
    event.preventDefault();
    if($("#updateId").val() == null || $("#updateId").val() == "")
    {
        store();
    } else {
        update();
    }
})

/*
    show modal for creating a record and
    empty the values of form and remove existing alerts
*/
window.addNew = function()
{
    $("#alert-div").html("");
    $("#error-div").html("");
    $("#updateId").val("");
    $("#name").val("");
    $("#form-modal").modal('show');
}

/*
    submit the form and will be stored to the database
*/
function store()
{
    $("#saveBtn").prop('disabled', true);
    let url = $('meta[name=app-url]').attr("content") + "/projects";
    let data = {
        name: $("#name").val(),
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: "POST",
        data: data,
        success: function(response) {
            $("#saveBtn").prop('disabled', false);
            let successHtml = '<div class="alert alert-success" role="alert"><b>Project Created Successfully</b></div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            reloadTable();
            $("#form-modal").modal('hide');
        },
        error: function(response) {
            $("#saveBtn").prop('disabled', false);
            if (typeof response.responseJSON.errors !== 'undefined')
            {
                let errors = response.responseJSON.errors;
                let nameValidation = "";
                if (typeof errors.name !== 'undefined')
                {
                    nameValidation = '<li>' + errors.name[0] + '</li>';
                }

                let errorHtml = '<div class="alert alert-danger" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '<ul>' + nameValidation + '</ul>' +
                    '</div>';
                $("#error-div").html(errorHtml);
            }
        }
    });
}


/*
    edit record function
    it will get the existing value and show the project form
*/
$(document).on('click', '.editBtn', function ()
{
    let id = 1;
    let url = "/api/items/" + id +"";
    $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
            let item = response.data;
            $("#alert-div").html("");
            $("#error-div").html("");
            $("#updateId").val(item.id);
            $("#name").val(item.name);
            $("#form-modal").modal('show');
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
});

/*
    sumbit the form and will update a record
*/
function update()
{
    $("#saveBtn").prop('disabled', true);
    let url = $('meta[name=app-url]').attr("content") + "/projects/" + $("#updateId").val();
    let data = {
        id: $("#updateId").val(),
        name: $("#name").val(),
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: "PUT",
        data: data,
        success: function(response) {
            $("#saveBtn").prop('disabled', false);
            let successHtml = '<div class="alert alert-success" role="alert"><b>Project Updated Successfully</b></div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            reloadTable();
            $("#form-modal").modal('hide');
        },
        error: function(response) {
            $("#saveBtn").prop('disabled', false);
            if (typeof response.responseJSON.errors !== 'undefined')
            {
                let errors = response.responseJSON.errors;
                let nameValidation = "";
                if (typeof errors.name !== 'undefined')
                {
                    nameValidation = '<li>' + errors.name[0] + '</li>';
                }

                let errorHtml = '<div class="alert alert-danger" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '<ul>' + nameValidation + '</ul>' +
                    '</div>';
                $("#error-div").html(errorHtml);
            }
        }
    });
}

/*
    get and display the record info on modal
*/
$(document).on('click', '.showBtn', function (){

    $("#name-info").html("");
    let id = 1;
    let url = "/api/items/" + id +"";
    $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
            let item = response.data;
            $("#name-info").html(item.name);
            $("#view-modal").modal('show');

        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
});

/*
    delete record function
*/
$(document).on('click', '.destroyBtn', function ()
{
    if(confirm("Are You sure want to delete !") === false){
        return
    }
    let id = 1;
    let url = "/api/items/" + id;
    let data = {
        name: $("#name").val(),
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: "DELETE",
        data: data,
        success: function(response) {
            let successHtml = '<div class="alert alert-success" role="alert"><b>Project Deleted Successfully</b></div>';
            $("#alert-div").html(successHtml);
            reloadTable();
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
});

import { createApp } from 'vue/dist/vue.esm-bundler.js';
window.Vue = createApp();

window.Vue.component('example-component', require('../components/ExampleComponent.vue').default);

window.Vue.mount('#app');
