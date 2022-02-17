@extends('adminlte.page')

@section('title', 'Login Page')

@section('adminlte_css')
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,400;0,600;1,200;1,400;1,600&display=swap"
    rel="stylesheet">
<style>
    a{
        color:black;
    }

    .login-page{
        font-family: 'Poppins', sans-serif;
        background-color: #e9ecef;
    }

    .flex-container {
        display: flex;

    }

    .flex-container > div {
        background-color: #ffffff;
        margin: 10px;
        padding: 20px;
    }

    .card {
        box-shadow: none;
        transition: 0.3s;
        text-align: center;

    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        color:dodgerblue;
    }

    .container {
        padding: 2px 16px;

    }

    img{
        width:100%;
        object-fit: contain;
        object-position: center;
    }

    .flex-container {
        display: flex;
    }

    @media screen and (min-width: 1350px) {
        .card-title{
            width:210px;
            height:210px;
        }

        .flex-container {
            max-width: 1350px;
        }

        .card {
            width: 250px;
        }

        .login-page{
            font-size: 15px;
        }
    }

    @media screen and (max-width: 1349px) {
        .card-title{
            width:180px;
            height:180px;
        }

        .flex-container {
            max-width: 1200px;
        }

        .card {
            width: 210px;
        }

        .login-page{
            font-size: 12px;
        }
    }

</style>
@stop


@section('body')
<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand mb-0 h1">PT. Sinko Prima Alloy</span>
    <span class="navbar-brand mb-0 float-right"><a type="button" class="btn btn-outline-danger" href="">Logout</a></span>
</nav>
<div class="login-page">
    <div class="row">
        <div class="col-12"><h5>Selamat Datang, Stephanie Kotanti. Silahkan Pilih Aksesmu!</h5></div>
    </div>
    <div class="flex-container d-flex flex-wrap justify-content-center">
        <div class="card" id="direksi" hidden>
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/direksi_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#" data-id="2">Direksi</a></h6>
            </div>
        </div>
        <div class="card" id="penjualan" hidden>
            <div class="card-title" >
                <img class="card-img-top" src="{{ asset('assets/image/dash/penjualan_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#" data-id="26">Penjualan</a></h6>
            </div>
        </div>
        <div class="card" id="ppic" hidden>
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/ppic_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">PPIC</a></h6>
            </div>
        </div>
        <div class="card" id="produksi" hidden>
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/prd_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Produksi</a></h6>
            </div>
        </div>
        <div class="card" id="gbj" hidden>
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/gbj_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Gudang Barang Jadi</a></h6>
            </div>
        </div>
        <div class="card" id="gk" hidden>
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/gk_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Gudang Karantina</a></h6>
            </div>
        </div>
        <div class="card" id="qc">
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/qc_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Quality Control</a></h6>
            </div>
        </div>
        <div class="card" id="logistik">
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/logistik_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Logistik</a></h6>
            </div>
        </div>
        <div class="card" id="as">
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/as_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#" data-id="8">After Sales</a></h6>
            </div>
        </div>
        <div class="card" id="dc">
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/dc_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Document Control</a></h6>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <img class="card-img-top" src="{{ asset('assets/image/dash/teknik_dash.jpg') }}" alt="Card image cap">
            </div>
            <div class="card-body">
              <h6><a href="#">Teknik</a></h6>
            </div>
        </div>
    </div>
</div>
@stop
