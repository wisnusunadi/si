@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1 class="m-0 text-dark">PPIC Jadwal Produksi</h1>
</div>
@stop

@section('content')
<div id="app">
    <calendar auth="{{ Auth::user() }}" />
</div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/jadwal.js') }}"></script>
@stop