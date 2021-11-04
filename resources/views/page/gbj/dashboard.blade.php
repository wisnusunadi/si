@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#dashboard-produk" class="nav-link active" id="dashboard-produk-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Produk</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#dashboard-transfer" class="nav-link" id="dashboard-transfer-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Transfer</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">

                    {{-- Produk --}}
                    <div class="tab-pane fade show active card-body" id="dashboard-produk" role="tabpanel"
                        aria-labelledby="dashboard-produk-tab"></div>
                        

                    {{-- Transfer --}}
                    <div class="tab-pane fade show active card-body" id="dashboard-transfer" role="tabpanel"
                        aria-labelledby="dashboard-transfer-tab"></div>
                    <div class="row"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>

</script>
@stop
