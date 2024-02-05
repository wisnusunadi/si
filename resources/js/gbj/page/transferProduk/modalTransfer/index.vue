<script>
import noseri from './noseri.vue'
export default {
    props: ['data'],
    components: {
        noseri
    },
    data() {
        return {
            search: '',
            produk: [
                {
                    detail_pesanan_id: 1,
                    nama_paket: 'DIGIT-ONE BABY + TAS',
                    jml_qc_paket: 0,
                    persentase_qc_paket: 0,
                    jml_gdg_paket: 1,
                    persentase_gudang_paket: 100,
                    produk: [
                        {
                            id: 1,
                            nama_produk: 'DIGIT ONE BABY',
                            jml: 1,
                            merk: 'ELITECH',
                            jml_qc: 0,
                            persentase_qc: 0,
                            jml_gdg: 1,
                            persentase_gudang: 100,
                        },
                        {
                            id: 2,
                            nama_produk: 'TAS',
                            jml: 1,
                            merk: 'ELITECH',
                            jml_qc: 0,
                            persentase_qc: 0,
                            jml_gdg: 1,
                            persentase_gudang: 100,
                        }
                    ]
                },
                {
                    detail_pesanan_id: 2,
                    nama_paket: 'DIGIT-PRO IDA',
                    jml_qc_paket: 0,
                    persentase_qc_paket: 0,
                    jml_gdg_paket: 1,
                    persentase_gudang_paket: 100,
                    produk: [
                        {
                            id: 3,
                            nama_produk: 'DIGIT PRO IDA COKLAT',
                            jml: 1,
                            merk: 'ELITECH',
                            jml_qc: 0,
                            persentase_qc: 0,
                            jml_gdg: 1,
                            persentase_gudang: 100,
                        },
                    ]
                }
            ],
            showModal: false,
            detailSelected: null,
            paketSelected: null
        }
    },
    methods: {
        closeModal() {
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        showModalNoseri(detail, paket) {
            this.detailSelected = detail
            this.paketSelected = paket
            this.showModal = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
                $('.modalTransfer').modal('hide')
            })
        },
        closeModalNoseri() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalNoSeri').modal('hide')
                $('.modalTransfer').modal('show')
            })
        },
        noseriSelected(paket) {
            this.produk = this.produk.map(p => {
                if (p.detail_pesanan_id === paket.detail_pesanan_id) {
                    p = paket
                }
                return p
            })
        }
    },
    computed: {
        filterRecursive() {
            const includesSearch = (obj, search) => {
                return Object.keys(obj).some(key => {
                    if (typeof obj[key] === 'object') {
                        return includesSearch(obj[key], search)
                    }
                    return String(obj[key]).toLowerCase().includes(search.toLowerCase())
                })
            }
            return this.produk.filter(paket => {
                return includesSearch(paket, this.search)
            })
        }
    }
}
</script>
<template>
    <div>
        <noseri v-if="showModal" :detailSelected="detailSelected" @close="closeModalNoseri" :paket="paketSelected"
            :allPaket="produk"  
            @submit="noseriSelected" />
        <div class="modal fade modalTransfer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nomor SO</label>
                                        <div class="card text-white" style="background-color: #717FE1;">
                                            <div class="card-body">
                                                <span id="so">{{ data.so }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor AKN</label>
                                        <div class="card text-white" style="background-color: #DF7458;">
                                            <div class="card-body">
                                                <span id="akn">{{ data.ekatalog?.no_paket ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card text-white" style="background-color: #85D296;">
                                            <div class="card-body">
                                                <span id="po">{{ data.no_po }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Merk</th>
                                            <th>Progress</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="filterRecursive.length > 0">
                                        <template v-for="paket in filterRecursive">
                                            <tr class="table-dark">
                                                <td colspan="5">
                                                    {{ paket.nama_paket }} <br>
                                                    <span class="badge badge-light">QC: {{ paket.jml_qc_paket }} ({{ paket.
                                                        persentase_qc_paket }}%)</span>
                                                    <span class="badge badge-warning">
                                                        Gudang: {{ paket.jml_gdg_paket }} ({{ paket.persentase_gudang_paket
                                                        }}%)
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-for="item in paket.produk" :key="item.nama_produk">
                                                <td>{{ item.nama_produk }}</td>
                                                <td>{{ item.jml }}</td>
                                                <td>{{ item.merk }}</td>
                                                <td>
                                                    <span class="badge badge-danger">QC: {{ item.jml_qc }} ({{
                                                        item.persentase_qc
                                                    }}%)</span> <br>
                                                    <span class="badge badge-light">Gudang: {{ item.jml_gdg }} ({{
                                                        item.persentase_gudang }}%)</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" @click="showModalNoseri(item, paket)">
                                                        <i class="fa fa-qrcode"></i>
                                                        Scan Barcode
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="100%" class="text-center">Tidak ada data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Transfer</button>
                        <button class="btn btn-info">Batalkan Persiapan</button>
                        <button class="btn btn-secondary" @click="closeModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>