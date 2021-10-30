@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<h1 class="m-0 text-dark">PPIC Dashboard</h1>
@stop

@section('content')
<div id="app"></div>
<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div id="wrapper">
                <div class="content-area">
                    <div class="container-fluid">
                        <div class="main">
                            <div class="row sparkboxes mt-4 mb-4">
                                <div class="col-md-4">
                                    <div class="card p-3">
                                        <div id="spark1"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card p-3">
                                        <div id="spark2"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card p-3">
                                        <div id="spark3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5 mb-4">
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <div id="bar"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <div id="donut"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 mb-4">
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <div id="area"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <div id="line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/dashboard.js') }}"></script>
@stop