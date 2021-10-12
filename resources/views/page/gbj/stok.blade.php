@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">GBJ</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div id="app"></div>
    </div>
</div>
@stop

@section('adminlte_js')
<!-- <script src="{{ asset('native/js/ppic/gbmp.js') }}"></script> -->
<script src="{{ asset('native/js/gbj/stok.js') }}"></script>
@stop