@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Items</h1>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary float-right"
                       onclick="addNew()">
                        Add New
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('items.table')
        </div>
    </div>
    <example-component></example-component>
    <v-btn>
        Button
    </v-btn>
    <v-color-picker></v-color-picker>
@endsection
