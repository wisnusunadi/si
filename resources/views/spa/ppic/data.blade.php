@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<div>
    @if ($status == 'gbj')
    <h1>Data Gudang Barang Jadi</h1>
    @elseif ($status == 'gk')
    <h1>Data Gudang Karantina</h1>
    @elseif ($status == 'perakitan')
    <h1>Data Perakitan</h1>
    @elseif ($status == 'so')
    <h1>Data SO</h1>
    @endif
</div>
@stop

@section('content')
<div id="app"></div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/data.js') }}"></script>
@stop