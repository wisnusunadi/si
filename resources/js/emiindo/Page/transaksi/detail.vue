<script>
import moment from 'moment';
export default {
    props: ['detail'],
    methods: {
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Batas Kontrak Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
    },
}
</script>
<template>
    <div class="modal fade modalDetail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-light cardremoveshadow">
                        <div class="card-body">
                            <h5 class="pl-2 py-2">
                                <b>{{ detail?.nama_customer }}</b>
                            </h5>
                            <ul class="fa-ul card-text" v-if="detail.jenis == 'ekatalog'">
                                <li class="py-2">
                                    <span class="fa-li"><span class="far fa-building fa-fw"></span></span>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2">Instansi</div>
                                        <div class="col-lg-11 col-md-10">
                                            {{ detail?.instansi }}
                                        </div>
                                    </div>
                                </li>
                                <li class="py-2"><span class="fa-li"><i class="fas fa-user-alt fa-fw"></i></span>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2">Satuan</div>
                                        <div class="col-lg-11 col-md-10">
                                            {{ detail?.satuan }}
                                        </div>
                                    </div>
                                </li>
                                <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2">Alamat</div>
                                        <div class="col-lg-11 col-md-10">
                                            {{ detail?.alamat }}
                                        </div>
                                    </div>

                                </li>
                                <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-2">Provinsi</div>
                                        <div class="col-lg-11 col-md-10">
                                            <em class="text-muted" v-if="!detail?.provinsi">Belum Tersedia</em>
                                            {{ detail?.provinsi?.nama }}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul class="fa-ul card-text" v-else>
                                <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                                    {{ detail?.customer?.alamat }}
                                </li>
                                <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                                    {{ detail?.provinsi?.nama }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card card-purple card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill"
                                        href="#tabs-detail" role="tab" aria-controls="tabs-detail"
                                        aria-selected="true">Informasi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk"
                                        role="tab" aria-controls="tabs-produk" aria-selected="false">Produk</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel"
                                    aria-labelledby="tabs-detail-tab">

                                    <div class="row d-flex justify-content-between" v-if="detail.jenis == 'ekatalog'">
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b>
                                                        {{ detail.so }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">No AKN</small></div>
                                                <div><b>
                                                        {{ detail.no_paket }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">No Urut</small></div>
                                                <div><b>{{ detail.no_urut }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">Tgl Buat</small></div>
                                                <div><b>
                                                        {{ detail.tgl_buat }}
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tgl Edit</small></div>
                                                <div><b>
                                                        {{ detail.tgl_edit }}
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tgl Delivery</small></div>
                                                <div v-if="detail.tgl_kontrak_custom"><b>
                                                        <div>{{ dateFormat(detail.tgl_kontrak_custom) }}</div>
                                                        <div><small
                                                                :class="calculateDateFromNow(detail.tgl_kontrak_custom).color"><i
                                                                    :class="calculateDateFromNow(detail.tgl_kontrak_custom).icon"></i>
                                                                {{ calculateDateFromNow(detail.tgl_kontrak_custom).text
                                                                }}
                                                            </small></div>
                                                    </b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b>
                                                        {{ detail.no_po }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                <div><b>
                                                        {{ dateFormat(detail.pesanan.tgl_po) }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                <persentase :persentase="detail.persentase" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-between" v-else>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b>
                                                        {{ detail.so }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                <persentase :persentase="detail.persentase" />
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b>
                                                        {{ detail.no_po }}
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                <div><b>
                                                        {{ detail.tgl_order }}
                                                    </b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No DO</small></div>
                                                <div><b v-if="detail.no_do">
                                                        {{ detail.no_do }}
                                                    </b>
                                                    <em v-else>Belum ada</em>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal DO</small></div>
                                                <div><b v-if="detail?.pesanan?.tgl_do">
                                                        {{ dateFormat(detail.pesanan.tgl_do) }}
                                                    </b>
                                                    <em v-else>Belum ada</em>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-success" role="alert" v-if="detail.jenis == 'ekatalog'">
                                        <strong>Deskripsi: </strong>
                                        <p>{{ detail.deskripsi }}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-produk" role="tabpanel"
                                    aria-labelledby="tabs-produk-tab">

                                    <div class="row">
                                        <div class="card col-lg-4 col-md-12 col-sm-12 removeshadow">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <canvas id="myChart" width="400" height="400"
                                                            class="mb-5"></canvas>
                                                        <div class="card card-secondary card-outline mt-3">
                                                            <div class="card-body">
                                                                <h3 class="profile-username text-center"><span
                                                                        id="nama_prd">-</span></h3>
                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                    <li class="list-group-item">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-orange mr-2"></span><span>Gudang</span></span>
                                                                        <a class="float-right mr-2"><b><span
                                                                                    id="c_gudang"
                                                                                    class="text-danger">0</span></b><sub
                                                                                id="tot_gudang"> dari 0</sub></a>
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-yellow mr-2"></span><span>QC</span></span>
                                                                        <a class="float-right mr-2"><b><span id="c_qc"
                                                                                    class="text-danger">0</span></b><sub
                                                                                id="tot_qc"> dari 0</sub></a>
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-green mr-2"></span><span>Logistik</span></span>
                                                                        <a class="float-right mr-2"><b><span id="c_log"
                                                                                    class="text-danger">0</span></b><sub
                                                                                id="tot_log"> dari 0</sub></a>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item bg-chart-blue text-white">
                                                                        <span class="align-self-center"><span
                                                                                class="foo mr-2"></span><b>Kirim</b></span>
                                                                        <b class="float-right mr-2"><span
                                                                                id="c_kirim">0</span> <sub>unit</sub>
                                                                        </b>
                                                                    </li>
                                                                </ul>
                                                                <div class="alert alert-info show" role="alert">
                                                                    <small>
                                                                        <i class="fas fa-info-circle"></i>
                                                                        <strong>Catatan:
                                                                        </strong>
                                                                        <ol
                                                                            style="list-item-style:none; margin-left:0px;padding-left:15px;">
                                                                            <li>Angka warna <b
                                                                                    class="text-danger">merah</b>
                                                                                menunjukkan jumlah unit yang <i>belum
                                                                                    diproses</i> oleh divisi tersebut
                                                                            </li>
                                                                            <li>Angka warna <b
                                                                                    class="text-dark">hitam</b>
                                                                                menunjukkan total yang <i>telah
                                                                                    diberikan dan
                                                                                    harus diproses</i> oleh divisi
                                                                                tersebut</li>
                                                                            <li>Angka pada Kirim merupakan total unit
                                                                                yang
                                                                                <i>telah terkirim</i>
                                                                            </li>
                                                                        </ol>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-lg-8 col-md-12">
                                            <div class="card-body">
                                                <h6><b>Detail Produk</b></h6>
                                                <div class="table-responsive overflowcard">
                                                    <table class="table" style="max-width:100%; overflow-x: hidden;"
                                                        id="tabledetailpesan">
                                                        <thead class="bg-chart-light">
                                                            <tr>
                                                                <th rowspan="2">No</th>
                                                                <th rowspan="2">Produk</th>
                                                                <th rowspan="2"></th>
                                                                <th rowspan="2">Qty</th>
                                                                <th rowspan="2">Harga</th>
                                                                <th rowspan="2">Ongkir</th>
                                                                <th rowspan="2">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>




                                                            <tr>
                                                                <td rowspan="2" class="nowraptxt">1</td>
                                                                <td><b class="wb">SONOTRAX-B</b>
                                                                </td>
                                                                <td class="nowraptxt">
                                                                    <button class="btn btn-sm btn-outline-primary"
                                                                        id="lihatstok" data-id="19417"
                                                                        data-produk="paket"><i
                                                                            class="fas fa-eye"></i></button>
                                                                </td>
                                                                <td class="nowraptxt">1
                                                                </td>
                                                                <td rowspan="2" class="nowraptxt tabnum">Rp. 2.475.000
                                                                </td>
                                                                <td rowspan="2" class="nowraptxt tabnum">Rp. 0</td>
                                                                <td rowspan="2" class="nowraptxt tabnum">Rp. 2.475.000
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="text-muted">
                                                                        SONOTRAX-B
                                                                    </span>
                                                                </td>
                                                                <td class="nowraptxt">
                                                                    <button class="btn btn-sm btn-outline-primary"
                                                                        id="lihatstok" data-id="29251"
                                                                        data-produk="variasi"><i
                                                                            class="fas fa-eye"></i></button>
                                                                </td>
                                                                <td>
                                                                    1
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot class="bg-chart-light align-center">
                                                            <tr>
                                                                <th colspan="6">Total Harga</th>
                                                                <th class="nowraptxt tabnum">Rp. 2.475.000</th>
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
        </div>
    </div>
</template>
<style>
.cardremoveshadow {
    box-shadow: none;
}
</style>