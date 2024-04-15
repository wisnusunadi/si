<script>
import moment from 'moment';
import Header from './header.vue'
import axios from 'axios';
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale)
export default {
    components: {
        Pie,
        Header
    },
    props: ['detail'],
    data() {
        return {
            paket: [],
            chartData: {},
            pengiriman: null
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            })
        },
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
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
        async getDataProduct() {
            try {
                const { data } = await axios.get(`/api/penjualan/items/${this.detail.pesanan_id}`);
                this.paket = data;
            } catch (error) {
                console.error(error);
            }
        },
        subtotal(harga, ongkir, jumlah) {
            return (harga * jumlah) + ongkir;
        },
        totalHarga() {
            return this.paket.reduce((acc, item) => {
                return acc + this.subtotal(item.harga, item.ongkir, item.jumlah);
            }, 0);
        },
        async getChartPengiriman(id, jenis) {
            try {
                const { data } = await axios.get(`/api/get_stok_pesanan?id=${id}&jenis=${jenis}`)
                this.pengiriman = data;

                if (jenis == 'part') {
                    this.chartData = {
                        labels: ['QC',
                            'Logistik',
                            'Kirim'],
                        datasets: [
                            {
                                backgroundColor: ['rgb(255, 221, 0)',
                                    'rgb(11, 171, 100)',
                                    'rgb(8, 126, 225)'],
                                data: [data.qc, data.log, data.kir]
                            }
                        ]
                    }
                } else {
                    this.chartData = {
                        labels: [
                            'Gudang',
                            'QC',
                            'Logistik',
                            'Kirim',
                        ],
                        datasets: [
                            {
                                backgroundColor: [
                                    'rgb(236, 159, 5)',
                                    'rgb(255, 221, 0)',
                                    'rgb(11, 171, 100)',
                                    'rgb(8, 126, 225)',
                                    // 'rgb(4, 200, 200)',
                                    // 'rgb(241, 65, 108)',
                                ],
                                data: [data.gudang, data.qc, data.log, data.kir]
                            }
                        ]
                    }
                }
            } catch (error) {
                console.error(error);
            }
        }
    },
    created() {
        this.getDataProduct();
    },
}
</script>
<template>
    <div class="modal fade modalDetail" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <Header :detail="detail" />
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
                                                <persentase :persentase="detail.persentase"
                                                    v-if="!cekIsString(detail.persentase)" />
                                                <span class="red-text badge" v-else>{{ detail.persentase }}</span>
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
                                        <div class="card col-lg-4 col-md-12 col-sm-12" v-if="pengiriman">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <Pie :chart-data="chartData" />
                                                        <div class="card card-secondary card-outline mt-3">
                                                            <div class="card-body">
                                                                <h3 class="profile-username text-center"><span
                                                                        id="nama_prd">{{
                                                                        pengiriman.detail.penjualan_produk.nama
                                                                        }}</span></h3>
                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                    <li class="list-group-item"
                                                                        v-if="pengiriman.detail.jenis != 'part'">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-orange mr-2"></span><span>Gudang</span></span>
                                                                        <a class="float-right mr-2"><b><span
                                                                                    id="c_gudang" class="text-danger">{{
                                                                                    pengiriman.gudang }}</span></b><sub
                                                                                id="tot_gudang"> dari {{
                                                                                pengiriman.detail.count_jumlah
                                                                                }}</sub></a>
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-yellow mr-2"></span><span>QC</span></span>
                                                                        <a class="float-right mr-2"><b><span id="c_qc"
                                                                                    class="text-danger">{{ pengiriman.qc
                                                                                    }}</span></b><sub id="tot_qc">
                                                                                dari
                                                                                {{ pengiriman.detail.count_gudang
                                                                                }}</sub></a>
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <span class="align-self-center"><span
                                                                                class="foo bg-chart-green mr-2"></span><span>Logistik</span></span>
                                                                        <a class="float-right mr-2"><b><span id="c_log"
                                                                                    class="text-danger">{{
                                                                                    pengiriman.log }}</span></b><sub
                                                                                id="tot_log"> dari {{
                                                                                pengiriman.detail.count_qc_ok
                                                                                }}</sub></a>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item bg-chart-blue text-white">
                                                                        <span class="align-self-center"><span
                                                                                class="foo mr-2"></span><b>Kirim</b></span>
                                                                        <b class="float-right mr-2"><span
                                                                                id="c_kirim">{{ pengiriman.kir }}</span>
                                                                            <sub>unit</sub> </b>
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
                                        <div class="card"
                                            :class="pengiriman ? 'col-lg-8 col-md-12 col-sm-12' : 'col-lg-12 col-md-12 col-sm-12'">
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
                                                            <template v-for="(item, idx) in paket">
                                                                <tr>
                                                                    <td :rowspan="item.jenis != 'part' ? item.produk.length + 1 : ''"
                                                                        class="nowraptxt">{{ idx + 1 }}
                                                                    </td>
                                                                    <td class="text-center"><b class="wb">{{ item.nama
                                                                            }}</b></td>
                                                                    <td class="nowraptxt">
                                                                        <button class="btn btn-sm btn-outline-primary"
                                                                            @click="getChartPengiriman(item.id, item.jenis)"><i
                                                                                class="fas fa-eye"></i></button>
                                                                    </td>
                                                                    <td class="nowraptxt">
                                                                        {{ item.jumlah }}
                                                                    </td>
                                                                    <td :rowspan="item.jenis != 'part' ? item.produk.length + 1 : ''"
                                                                        class="nowraptxt tabnum">{{
                                                                        rupiahFormat(item.harga) }}</td>
                                                                    <td :rowspan="item.jenis != 'part' ? item.produk.length + 1 : ''"
                                                                        class="nowraptxt tabnum">
                                                                        {{ rupiahFormat(item.ongkir) }}</td>
                                                                    <td :rowspan="item.jenis != 'part' ? item.produk.length + 1 : ''"
                                                                        class="nowraptxt tabnum">{{
                                                                        rupiahFormat(subtotal(item.harga, item.ongkir,
                                                                        item.jumlah)) }}
                                                                    </td>
                                                                </tr>
                                                                <tr v-for="(produk, idx2) in item.produk">
                                                                    <td class="text-center">
                                                                        <span class="text-muted">
                                                                            {{ produk.nama }}
                                                                        </span>
                                                                        <br>
                                                                        <h6 v-if="item.jumlah_retur"><span
                                                                                class="badge badge-info">Retur:
                                                                                {{ item.jumlah_retur }}</span></h6>
                                                                        <h6 v-if="item.jumlah_batal"><span
                                                                                class="badge badge-orange">Batal:
                                                                                {{ item.jumlah_batal }}</span></h6>
                                                                    </td>
                                                                    <td class="nowraptxt">
                                                                        <button class="btn btn-sm btn-outline-primary"
                                                                            @click="getChartPengiriman(produk.id, produk.jenis)"><i
                                                                                class="fas fa-eye"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        {{ item.jumlah }}
                                                                    </td>
                                                                </tr>
                                                            </template>

                                                        </tbody>
                                                        <tfoot class="bg-chart-light align-center">
                                                            <tr>
                                                                <th colspan="6">Total Harga</th>
                                                                <th class="nowraptxt tabnum">
                                                                    {{ rupiahFormat(totalHarga()) }}
                                                                </th>
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

.bg-chart-blue {
    background: rgb(8, 126, 225);
}

.badge-orange {
    background: #f39c12;
    color: white;
}
</style>