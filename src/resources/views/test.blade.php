@extends('web::layouts.grids.12')

@section('title', "Title")
@section('page_header', "Title")


@section('full')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">About</h3>
        </div>
        <div class="card-body">

            @include("treelib::editAccessControl")
        </div>
    </div>
@stop
