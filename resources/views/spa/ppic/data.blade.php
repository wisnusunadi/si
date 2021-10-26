@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content')
<div id="app"></div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/data.js') }}"></script>
@stop