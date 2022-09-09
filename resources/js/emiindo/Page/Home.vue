<template>
    <div>
        <div v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="container-fluid" v-else>
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="salesorder-tab" data-toggle="tab" href="#salesorder" role="tab"
                            aria-controls="salesorder" aria-selected="false" @click="tab = 'salesorder'">E-Katalog</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="purchaseorder-tab" data-toggle="tab" href="#purchaseorder" role="tab"
                            aria-controls="purchaseorder" @click="tab = 'purchaseorder'" aria-selected="false">Purchase
                            Order</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="deliveryorder-tab" data-toggle="tab" href="#deliveryorder" role="tab"
                            aria-controls="deliveryorder" @click="tab = 'deliveryorder'" aria-selected="false">Delivery
                            Order</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="salesorder" role="tabpanel"
                        aria-labelledby="salesorder-tab" v-show="tab = 'salesorder'">
                        <table class="table tableSo">
                            <thead>
                                <tr>
                                    <th>Nomor AKN</th>
                                    <th>Nama Customer</th>
                                    <th>Total Harga</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in dataSO" :key="idx">
                                    <td>{{item.epurno}}</td>
                                    <td>{{ item.satuankerja.customername }}</td>
                                    <td>Rp. {{ formatRupiah(totalHargaSOEkat(item.sodetail)) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" @click="detail(item.epurno)"><i
                                                class="fas fa-eye"></i>
                                            Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade card-body" id="purchaseorder" role="tabpanel"
                        aria-labelledby="purchaseorder-tab" v-show="tab = 'purchaseorder'">
                        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-ekatalog-tab" data-toggle="pill"
                                    href="#pills-ekatalog" role="tab" aria-controls="pills-ekatalog"
                                    @click="tabPO = 'ekat'" aria-selected="true">E-Katalog</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-spa-tab" data-toggle="pill" href="#pills-spa" role="tab"
                                    @click="poNonEkat" aria-controls="pills-selesai_kirim" aria-selected="false">SPA</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-ekatalog" role="tabpanel"
                                v-show="tabPO == 'ekat'" aria-labelledby="pills-ekatalog-tab">
                                <table class="table tablePoEKat">
                                    <thead>
                                        <tr>
                                            <th>Nomor AKN</th>
                                            <th>Nomor PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Total Harga</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, idx) in dataPOEkat" :key="idx">
                                            <td>
                                                <span v-if="!item.salesorderno">-</span>
                                                <span v-else>{{item.salesorderno}}</span>
                                            </td>
                                            <td>{{item.pono}}</td>
                                            <td>{{ tgl_format(item.podate) }}</td>
                                            <td>Rp. {{ formatRupiah(totalHargaPO(item.purchaseorderdetail)) }}</td>
                                            <td>
                                                <div v-if="!item.salesorderno"></div>
                                                <button v-else class="btn btn-sm btn-outline-primary"
                                                    @click="detailPOEkat(item.poid)"><i class="fas fa-eye"></i>
                                                    Detail</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="pills-spa" role="tabpanel" v-show="tabPO == 'nonekat'"
                                aria-labelledby="pills-spa-tab">
                                <table class="table" id="tablePoNonEKat">
                                    <thead>
                                        <tr>
                                            <th>Nomor PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Total Harga</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, idx) in dataPONonEkat" :key="idx">
                                            <td>{{item.pono}}</td>
                                            <td>{{ tgl_format(item.podate) }}</td>
                                            <td>Rp. {{ formatRupiah(totalHargaPO(item.purchaseorderdetail)) }}</td>
                                            <td>
                                                <div v-if="item.pono == '0'"></div>
                                                <button v-else class="btn btn-sm btn-outline-primary"
                                                    @click="detailPONonEkat(item.poid)"><i class="fas fa-eye"></i>
                                                    Detail</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="deliveryorder" role="tabpanel"
                        aria-labelledby="deliveryorder-tab" v-show="tab = 'deliveryorder'">
                        <table class="table tableDO">
                            <thead>
                                <tr>
                                    <th>Nomor DO</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal DO</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in dataDO" :key="idx">
                                    <td>{{item.dono}}</td>
                                    <td>{{item.purchaseorder.pono}}</td>
                                    <td>{{ tgl_format(item.dodate) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            @click="detailDO(item.purchaseorder.poid)"><i class="fas fa-eye"></i>
                                            Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal SO -->
        <div class="modal fade modalSO" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalSO">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold" id="exampleModalLabel">Detail AKN {{ detailModalSO.epurno }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row filter">
                            <div class="col-12">
                                <div class="card card-detail removeshadow">
                                    <div class="card-body border-0">
                                        <h5 class="card-title pl-2 py-2">
                                            <b>
                                                {{ detailModalSO.epurno }}
                                            </b>
                                        </h5>
                                        <ul class="fa-ul card-text">
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-user-alt fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.customername }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-address-card fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.address }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-map-marker-alt fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.kota.provinsi.provnm }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card card-orange card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-informasi-tab" data-toggle="pill"
                                                    href="#tabs-informasi" role="tab" aria-controls="tabs-informasi" @click="tabModal = 'informasi'"
                                                    aria-selected="true">Informasi</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tabs-produk-tab" data-toggle="pill"
                                                    href="#tabs-produk" role="tab" aria-controls="tabs-produk" @click="tabModal = 'produk'"
                                                    aria-selected="false">Produk</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade active show" id="tabs-informasi" role="tabpanel" v-show="tabModal = 'informasi'"
                                                aria-labelledby="tabs-informasi-tab">

                                                <div class="row d-flex justify-content-between">

                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nama Instansi</small></div>
                                                            <div>
                                                                <b v-if="detailModalSO.instansi == null">-</b>
                                                                <b v-else>{{ detailModalSO.instansi.instansinm }}</b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Deskripsi</small></div>
                                                            <div><b>
                                                                    {{ detailModalSO.namapaket }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalSO.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal
                                                                    Edit</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalSO.editdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-produk" role="tabpanel" v-show="tabModal = 'produk'"
                                                aria-labelledby="tabs-produk-tab">
                                                <div class="table-responsive">
                                                    <div class="card removeshadow overflowy">
                                                        <div class="card-body">

                                                            <table class="table"
                                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                                id="tabledetailpesan">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2">No</th>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th colspan="2">Qty</th>
                                                                        <th rowspan="2">Harga</th>
                                                                        <th rowspan="2">Ongkos Kirim</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalSO.sodetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.datauom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.shippingcharge)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotal(item.qty,item.price,item.shippingcharge)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(total(detailModalSO.sodetail)) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                            @click="tambahSO(detailModalSO.epurno)">Terima</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal PO Ekatalog -->
        <div class="modal fade modalPOEkat" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalPOEkat">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold" id="exampleModalLabel">Detail PO E-Katalog {{ detailModalPOEkat.PO.pono }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row filter">
                            <div class="col-12">
                                <div class="card card-detail removeshadow">
                                    <div class="card-body border-0">
                                        <h5 class="card-title pl-2 py-2">
                                            <b>
                                                {{ detailModalPOEkat.PO.salesorderno }}
                                            </b>
                                        </h5>
                                        <div v-if="detailModalPOEkat.perusahaan == undefined"></div>
                                        <ul class="fa-ul card-text" v-else>
                                            <li class="py-2"><span class="fa-li"><i class="fas fa-user-alt fa-fw"></i>
                                            </span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.customername }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-address-card fa-fw"></i></span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.address }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-map-marker-alt fa-fw"></i></span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.kota.provinsi.provnm }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card card-orange card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-informasi2-tab" data-toggle="pill"
                                                    href="#tabs-informasi2" role="tab" aria-controls="tabs-informasi2" @click="tabModal1 = 'informasi'"
                                                    aria-selected="true">Informasi</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tabs-produk2-tab" data-toggle="pill"
                                                    href="#tabs-produk2" role="tab" aria-controls="tabs-produk2" @click="tabModal1 = 'produk'"
                                                    aria-selected="false">Produk</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade active show" id="tabs-informasi2" role="tabpanel" v-show="tabModal1 == 'informasi'"
                                                aria-labelledby="tabs-informasi2-tab">

                                                <div class="row d-flex justify-content-between">

                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nomor PO</small></div>
                                                            <div>
                                                                <b>{{ detailModalPOEkat.PO.pono }}</b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nama Paket</small></div>
                                                            <div>
                                                                <div v-if="detailModalPOEkat.perusahaan == undefined"><b>-</b></div>
                                                                <b v-else>
                                                                    {{ detailModalPOEkat.perusahaan.namapaket }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPOEkat.PO.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal PO</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPOEkat.PO.podate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-produk2" role="tabpanel" v-show="tabModal1 == 'produk'"
                                                aria-labelledby="tabs-produk2-tab">
                                                <div class="table-responsive">
                                                    <div class="card removeshadow overflowy">
                                                        <div class="card-body">

                                                            <table class="table"
                                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                                id="tabledetailpesan">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2">No</th>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th colspan="2">Qty</th>
                                                                        <th rowspan="2">Harga</th>
                                                                        <th rowspan="2">Diskon</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalPOEkat.PO.purchaseorderdetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.uom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">
                                                                            {{ parseInt(item.discount) }} %</td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotalPO(item.qty,item.price,item.discount)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(totalPO("ekat", detailModalPOEkat.PO)) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="tambahPO(detailModalPOEkat.PO.salesorderno, detailModalPOEkat.PO.pono)">Terima</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Po Non Ekatalog -->
        <div class="modal fade modalPONonEkat" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalPONonEkat">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold" id="exampleModalLabel">Detail PO SPA {{ detailModalPONonEkat.pono }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row filter">
                            <div class="col-12">
                                <div class="card card-orange card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-informasi3-tab" data-toggle="pill"
                                                    href="#tabs-informasi3" role="tab" aria-controls="tabs-informasi3" @click="tabModal2 = 'informasi'"
                                                    aria-selected="true">Informasi</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tabs-produk3-tab" data-toggle="pill"
                                                    href="#tabs-produk3" role="tab" aria-controls="tabs-produk3" @click="tabModal2 = 'produk'"
                                                    aria-selected="false">Produk</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade active show" id="tabs-informasi3" role="tabpanel" v-show="tabModal2 == 'informasi'"
                                                aria-labelledby="tabs-informasi3-tab">

                                                <div class="row d-flex justify-content-between">

                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nomor PO</small></div>
                                                            <div>
                                                                <b>{{ detailModalPONonEkat.pono }}</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPONonEkat.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal PO</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPONonEkat.podate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-produk3" role="tabpanel" v-show="tabModal2 == 'produk'"
                                                aria-labelledby="tabs-produk3-tab">
                                                <div class="table-responsive">
                                                    <div class="card removeshadow overflowy">
                                                        <div class="card-body">

                                                            <table class="table"
                                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                                id="tabledetailpesan">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2">No</th>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th colspan="2">Qty</th>
                                                                        <th rowspan="2">Harga</th>
                                                                        <th rowspan="2">Diskon</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalPONonEkat.purchaseorderdetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.uom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">
                                                                            {{ parseInt(item.discount) }} %</td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotalPO(item.qty,item.price,item.discount)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(totalPO("nonekat", detailModalPONonEkat)) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="tambahPONonEkat(detailModalPONonEkat.pono)">Terima</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal DO -->
        <div class="modal fade modalDO" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalDO">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold" id="exampleModalLabel">Detail NO DO {{ detailModalDO.dono }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="card">
                                    <div class="card-header">Info Pesanan</div>
                                    <div class="card-body">
                                        <div class="margin">
                                            <a class="text-muted">No PO</a>
                                            <b class="float-right">
                                                {{ detailModalDO.purchaseorder.pono }}
                                            </b>
                                        </div>

                                        <div class="margin">
                                            <a class="text-muted">Ekspedisi</a>
                                            <b class="float-right" v-if="detailModalDO.expedition == null">-</b>
                                            <b class="float-right" v-else>{{ detailModalDO.expedition.expnm }}</b>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="alert alert-danger" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong> {{ detailModalDO.donote }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">Informasi Perusahaan</div>
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label"
                                                            name="no_do" id="no_do" :value="detailModalDO.dono" readonly>
                                                        <div class="invalid-feedback" id="msgno_do"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for=""
                                                        class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label"
                                                            name="tgl_do" id="tgl_do" :value="tgl_format(detailModalDO.dodate)" readonly>
                                                        <div class="invalid-feedback" id="msgtgl_do"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for=""
                                                        class="col-form-label col-lg-5 col-md-12 labelket">Alamat
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        {{ detailModalDO.address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="tambahdo(detailModalDO)">Terima</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import mix from '../mixins/mix'
    export default {
        mixins: [mix],
        data() {
            return {
                loading: true,
                tab: 'salesorder',
                // SO
                tabModal: 'informasi',
                dataSO: [],
                detailModalSO: [],
                modalSO: false,
                checkEkat: [],
                // PO
                tabModal1: 'informasi',
                tabModal2: 'informasi',
                dataPOEkat: [],
                dataPONonEkat: [],
                detailModalPOEkat: [],
                detailModalPONonEkat: [],
                modalPOEkat: false,
                modalPONonEkat: false,
                checkEkatPO: [],
                checkNonEkatPO: [],
                tabPO: 'ekat',
                // DO
                dataDO: [],
                detailModalDO: [],
                modalDO: false,
                checkDO: [],
            }
        },
        methods: {
            async loadData() {
                try {
                    this.loading = true
                        await axios.get('https://sinko.api.hyperdatasystem.com/api/salesorder', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataSO = response.data
                        this.$store.commit('setDataSO', this.dataSO)
                    })
                    await axios.get('https://sinko.api.hyperdatasystem.com/api/purchaseorder?type=ECAT', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataPOEkat = response.data
                        this.$store.commit('setDataPOEkat', this.dataPOEkat)
                    })
                    await axios.get('https://sinko.api.hyperdatasystem.com/api/purchaseorder?type=NONECAT', {
                         headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataPONonEkat = response.data
                        this.$store.commit('setDataPONonEkat', this.dataPONonEkat)
                    })
                    await axios.get('https://sinko.api.hyperdatasystem.com/api/deliveryorder', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataDO = response.data
                        this.$store.commit('setDataDO', this.dataDO)
                    })
                    this.loading = false
                } catch (error) {
                    console.log(error);
                }
            },
            detail(id) {
                this.tabModal = 'informasi'
                this.detailModalSO = this.dataSO.find(item => item.epurno == id);
                this.modalSO = true;
                setTimeout(() => {
                    $('.modalSO').modal('show');
                }, 100);
            },
            detailPOEkat(id) {
                this.tabModal1 = 'informasi'
                let dataPO = this.dataPOEkat.find(item => item.poid == id);
                let dataPerusahaanPO = this.dataSO.find(item => item.epurno == dataPO.salesorderno);
                this.detailModalPOEkat = {
                    'PO': dataPO,
                    'perusahaan': dataPerusahaanPO
                };
                this.modalPOEkat = true;
                setTimeout(() => {
                    $('.modalPOEkat').modal('show');
                }, 100);
            },
            detailPONonEkat(id) {
                this.tabModal2 = 'informasi'
                this.detailModalPONonEkat = this.dataPONonEkat.find(item => item.poid == id);
                this.modalPONonEkat = true;
                setTimeout(() => {
                    $('.modalPONonEkat').modal('show');
                }, 100);
            },
            detailDO(id) {
                this.detailModalDO = this.dataDO.find(item => item.purchaseorder.poid == id);
                this.modalDO = true;
                setTimeout(() => {
                    $('.modalDO').modal('show');
                }, 100);
            },
            poNonEkat() {
                this.tabPO = 'nonekat'
                $('#tablePoNonEKat').DataTable();
            },
            async tambahSO(detail){
                try {
                    await axios.get('/api/penjualan/check_ekatalog/'+ detail).then(response => {
                        if(response.data.message == "Sudah Proses"){
                            this.$swal({
                                title: 'Gagal',
                                text: 'Data sudah di proses',
                                type: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.value) {
                                    try {
                                        let data = {
                                            refnumber: detail,
                                        }
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/salesorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            this.loadData()
                                        })
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }
                            })
                        }else{
                            this.$router.push({
                                name: 'detail',
                                params: {
                                    id: detail,
                                    jenis: 'ekatso'
                                }
                            });
                        }
                    $('.modalSO').modal('hide');
                    })
                } catch (error) {
                    console.log(error);
                }
            },
            async tambahPO(detail, nopo){
                try {
                    await axios.get('/api/penjualan/check_po/'+ nopo).then(response => {
                        console.log(response);
                        if(response.data.message == "Sudah Proses"){
                            this.$swal({
                                title: 'Gagal',
                                text: 'Data sudah di proses',
                                type: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.value) {
                                    try {
                                        let data = {
                                            refnumber: nopo,
                                        }
                                        this.loading = true;
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/purchaseorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            console.log("success");
                                        })
                                        if(response.data.do != null){
                                            let data =  {
                                                refnumber: response.data.do,
                                            }
                                            axios.post('https://sinko.api.hyperdatasystem.com/api/deliveryorder/save', data, {
                                                headers: {
                                                    Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                                },
                                            }).then(response => {
                                                this.loadData()
                                            })
                                        }else{
                                            this.loadData()
                                        }
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }
                            })
                        }else{
                        this.$router.push({
                            name: 'detail',
                            params: {
                                id: detail,
                                jenis: 'ekatpo'
                            }
                        });
                        }
                    $('.modalPOEkat').modal('hide');
                    })
                } catch (error) {
                    console.log(error);
                }
            },
            async tambahPONonEkat(detail){
                try {
                    await axios.get('/api/penjualan/check_po/'+ detail).then(response => {
                        console.log(response);
                        if(response.data.message == "Sudah Proses"){
                            this.$swal({
                                title: 'Gagal',
                                text: 'Data sudah di proses',
                                type: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.value) {
                                    try {
                                        let data = {
                                            refnumber: detail,
                                        }
                                        this.loading = true;
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/purchaseorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            console.log("success");
                                        })
                                        if(response.data.do != null){
                                            let data =  {
                                                refnumber: response.data.do,
                                            }
                                            axios.post('https://sinko.api.hyperdatasystem.com/api/deliveryorder/save', data, {
                                                headers: {
                                                    Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                                },
                                            }).then(response => {
                                                this.loadData()
                                            })
                                        }else{
                                            this.loadData()
                                        }
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }
                            })
                        }else{
                            this.$router.push({
                                name: 'detail',
                                params: {
                                    id: detail,
                                    jenis: 'nonekatpo'
                                }
                            });
                        }
                    $('.modalPONonEkat').modal('hide');
                    })
                } catch (error) {
                    console.log(error);
                }
            },
            tambahdo(dataDO){
                let datasavedo = {
                    no_po: dataDO.purchaseorder.pono,
                    no_do: dataDO.dono,
                    tgl_do: dataDO.dodate,
                }
                let datado = {
                    refnumber: dataDO.dono
                }
                this.$swal({
                    title: 'Apakah anda yakin?',
                    text: "Data yang anda pilih akan diterima",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Terima!'
                }).then((result) => {
                    if (result.value) {
                        axios.post("/penjualan/penjualan/store_do", datasavedo).then((response) => {
                            if (response.data.message != 'ponull') {
                                if(response.data.message == 'donotnull'){
                                    this.$swal({
                                        title: 'Gagal!',
                                        text: 'DO Sudah Terisi',
                                        icon: 'error',
                                        timer: '2000',
                                        confirmButtonText: 'OK'
                                    })
                                }else{
                                    this.$swal({
                                        title: 'Berhasil',
                                        text: 'DO Berhasil Dikirim',
                                        icon: 'success',
                                        timer: '2000',
                                        confirmButtonText: 'OK'
                                    })
                                }
                                axios.post('https://sinko.api.hyperdatasystem.com/api/deliveryorder/save', datado, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                         }).then(response => {
                                            this.loadData();
                                        })
                            } else {
                                this.$swal({
                                    title: 'Gagal!',
                                    text: 'PO Belum Terisi',
                                    icon: 'error',
                                    timer: '2000'
                                })
                            }
                        })
                    }
                    $('.modalDO').modal('hide');
                })
            },
            checkData(){
            if(this.dataSO.length > 0){
                this.loading = true;
                this.dataSO.forEach(item => {
                        axios.get('/api/penjualan/check_ekatalog/'+ item.epurno).then(response => {
                        if(response.data.message == "Sudah Proses"){
                            this.dataSO.splice(this.dataSO.indexOf(item), 1);
                            try {
                                let data = {
                                    refnumber: item.epurno,
                                                                }
                                axios.post('https://sinko.api.hyperdatasystem.com/api/salesorder/save', data, {
                                    headers: {
                                        Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                    },
                                }).then(response => {
                                    // this.loadData()
                                    this.loading = false;
                                })
                            } catch (error) {
                                console.log(error);
                            }
                        }
                    })
                });
            }
            // if(this.dataPOEkat.length > 0){
            //     this.loading = true;
            //     this.dataPOEkat.forEach(item => {
            //             axios.get('/api/penjualan/check_po/'+ item.pono).then(response => {
            //             if(response.data.message == "Sudah Proses"){
            //                 this.dataPOEkat.splice(this.dataPOEkat.indexOf(item), 1);
            //                 try {
            //                     let data = {
            //                         refnumber: item.pono,
            //                     }
            //                     axios.post('https://sinko.api.hyperdatasystem.com/api/purchaseorder/save', data, {
            //                         headers: {
            //                             Authorization: 'Bearer ' + sessionStorage.getItem('token')
            //                         },
            //                     }).then(response => {
            //                         // this.loadData()
            //                         this.loading = false;
            //                     })
            //                 } catch (error) {
            //                     console.log(error);
            //                 }
            //             }
            //         })
            //     });
            // }
        },
        async getToken(){
            let data = {
                username: "superuser.api",
                password: "password"
            }
            await axios.post('https://sinko.api.hyperdatasystem.com/api/login', data).then(response => {
                sessionStorage.setItem('token', response.data.token);
            }).catch(error => {
                console.log(error);
            })
        }
        },
        created() {
            this.loadData();
            // this.getToken();
        },
        // mounted() {
        // },
        beforeUpdate(){
            // this.checkData();
        },
        updated() {
            $('.tableSo').DataTable();
            $('.tablePoEKat').DataTable();
            $('.tableDO').DataTable();
        },
    }
</script>
<style scoped>
    ul#status {
        padding: 0;
    }
    ul#status li {
        /* float: left; */
        display: inline;
        padding: 0;
        list-style-type: none;
        margin: 0;
        /* To remove default bottom margin */
        /* margin: 10px; */
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    .separator {
        border-top: 1px solid #bbb;
        width: 90%;
    }
    .wb {
        word-break: break-all;
        white-space: normal;
    }
    .nowraptxt {
        white-space: nowrap;
    }
    .filter {
        margin: 5px;
    }
    /* thead {
            text-align: center;
        }
        td {
            text-align: center;
            white-space: nowrap;
        } */
    #urgent {
        color: #dc3545;
        font-weight: 600;
    }
    #warning {
        color: #FFC700;
        font-weight: 600;
    }
    #info {
        color: #3a7bb0;
        font-weight: 600;
    }
    .minimizechar {
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 13ch;
    }
    .hide {
        display: none;
    }
    .dropdown-toggle:hover {
        color: #4682B4;
    }
    .dropdown-toggle:active {
        color: #C0C0C0;
    }
    td.details-control {
        content: "\f055";
        font-family: FontAwesome;
        left: -5px;
        position: absolute;
        top: 0;
    }
    #detailekat {
        background-color: #E9DDE5;
    }
    #detailspa {
        background-color: #FFE6C9;
    }
    #detailspb {
        background-color: #E1EBF2;
        /* color: #7D6378; */
    }
    .tabnum {
        font-variant-numeric: tabular-nums;
    }
    .removeshadow {
        box-shadow: none;
    }
    .align-center {
        text-align: center;
    }
    .bordertopnone {
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        vertical-align: top;
    }
    .margin {
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .card-detail {
        align-items: center;
        flex-direction: row;
        shadow: none;
        border: none;
    }
    .card-detail img {
        width: 25%;
        border-top-right-radius: 0;
        border-bottom-left-radius: calc(0.25rem - 1px);
    }
    @media screen and (min-width: 1440px) {
        body {
            font-size: 14px;
        }
        #detailmodal {
            font-size: 14px;
        }
        .btn {
            font-size: 14px;
        }
        .overflowy {
            max-height: 550px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }
        .labelket {
            text-align: right;
        }
    }
    @media screen and (max-width: 1439px) {
        body {
            font-size: 12px;
        }
        h4 {
            font-size: 20px;
        }
        #detailmodal {
            font-size: 12px;
        }
        .btn {
            font-size: 12px;
        }
        .overflowy {
            max-height: 450px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }
        .labelket {
            text-align: right;
        }
    }
    @media screen and (max-width: 991px) {
        .labelket {
            text-align: left;
        }
    }
</style>
