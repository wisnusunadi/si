@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<div>
    @if ($status == 'penyusunan')
    <h1>Rencana Jadwal Perakitan</h1>
    @elseif ($status == 'pelaksanaan')
    <h1>Pelaksanaan Jadwal Perakitan</h1>
    @endif
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