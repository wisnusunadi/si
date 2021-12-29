@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    img{
        height: 100px;
    }
</style>
@stop

@section('content')
    
<div class="d-flex align-items-center justify-content-center" style="height: 500px">
    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/man_ppic.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">Man. PPIC</p>
        </div>
    </div>

    <div class="card card_2 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/ppic.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">PPIC</p>
        </div>
    </div>

    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/sales.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">Penjualan</p>
        </div>
    </div>

    <div class="card card_2 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/gbj.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">GBJ</p>
        </div>
    </div>

    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/production.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">Produksi</p>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-center">
    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/qc.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">QC</p>
        </div>
    </div>

    <div class="card card_2 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/logistik.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">Logistik</p>
        </div>
    </div>

    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/dc.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">DC</p>
        </div>
    </div>

    <div class="card card_2 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/after_sales.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">After Sales</p>
        </div>
    </div>

    <div class="card card_1 mr-5" style="width: 13rem;">
        <div class="card-body">
            <h4 class="card-title">
                <img class="card-img-top" src="{{ asset('assets/login/direksi.svg') }}" alt="">
            </h4>
            <p class="card-text text-center font-weight-bold text-primary">Direksi</p>
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
