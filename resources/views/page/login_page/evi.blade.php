@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    img{
        height: 500px;
    }
</style>
@stop

@section('content') 
    
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <div class="card card_1">
                <div class="card-body">
                    <h4 class="card-title">
                        <img class="card-img-top" src="./assets/sales.svg" alt="">
                    </h4>
                    <h1 class="card-text text-center font-weight-bold text-primary">Penjualan</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card card_2">
                <div class="card-body">
                    <h4 class="card-title">
                        <img class="card-img-top" src="./assets/health.svg" alt="">
                    </h4>
                    <h1 class="card-text text-center font-weight-bold text-primary">Kesehatan</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <button class="btn btn-primary btn-block btn-lg">Masuk</button>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
<script>
    $(document).ready(function(){
        $('.card_1').on('click', function(){
            $(this).toggleClass('shadow-lg');
            $('.card_2').removeClass('shadow-lg');  
        });

        $('.card_2').on('click', function(){
            $(this).toggleClass('shadow-lg');
            $('.card_1').removeClass('shadow-lg');  
        });
    });
</script>
@stop
