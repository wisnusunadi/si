@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div id="app"></div>
    </div>
</div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/gbj/so.js') }}"></script>
@stop