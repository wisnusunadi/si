@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Penjualan Eksternal</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#emiindo" class="nav-link active" id="emiindo-tab" role="tab" aria-controls="emiindo" aria-selected="false">PT Emiindo</a>
            </li>
        </ul>
        <div class="tab-content card" id="myTabContent">
            <div class="tab-pane fade show active card-body" id="emiindo" role="tabpanel" aria-labelledby="emiindo-tab">
                <span class="float-right filter">
                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </span>
            </div>
        </div>
    </div>
</section>
@endsection
@section('adminlte_js') 
@endsection