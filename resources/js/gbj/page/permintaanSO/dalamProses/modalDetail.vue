<script>
import modalNoSeri from './modalNoSeri.vue'
export default {
    props: ['detailSelected'],
    components: {
        modalNoSeri
    },
    data() {
        return {
            produk: [
                {
                    id: 1,
                    jumlah: 8,
                    jumlah_gudang: 0,
                    jumlah_sisa: 8,
                    nama: "TIMBANGAN DIGITAL IBU & ANAK DIGIT-PRO IDA NEW + LINEAR PROBE",
                    persentase_belum: 100,
                    persentase_sudah: 0,
                    item: [
                        {
                            id: 1,
                            variasi: [
                                {
                                    gbj_id: 1,
                                    label: "DIGIT PRO IDA NEW COKLAT"
                                },
                                {
                                    gbj_id: 2,
                                    label: "DIGIT PRO IDA NEW HIJAU"
                                },
                                {
                                    gbj_id: 3,
                                    label: "DIGIT PRO IDA NEW MERAH"
                                },
                            ],
                            variasiSelected: {
                                gbj_id: 1,
                                label: "DIGIT PRO IDA NEW COKLAT"
                            },
                            jumlah: 2,
                            status: false
                        },
                        {
                            id: 2,
                            variasi: [
                                {
                                    gbj_id: 1,
                                    label: "LINEAR PROBE"
                                },
                            ],
                            variasiSelected: {
                                gbj_id: 1,
                                label: "LINEAR PROBE"
                            },
                            jumlah: 2,
                            status: true
                        }
                    ]
                }
            ],
            search: '',
            showModalNoseri: false,
            detailSelectedNoSeri: {},
            selectedProduk: [],
            checkAll: false
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        closeModalNoseri() {
            this.showModalNoseri = false
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        openModalNoseri(item) {
            this.detailSelectedNoSeri = item
            this.showModalNoseri = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalNoseri').modal('show')
            })
        },
        checkedAll() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.produk.forEach(paket => {
                    paket.item.forEach(item => {
                        if (!item.status) {
                            this.selectedProduk.push(item)
                        }
                    })
                })
            } else {
                this.selectedProduk = []
            }
        },
        checkOne(item) {
            if (this.selectedProduk.includes(item)) {
                this.selectedProduk = this.selectedProduk.filter(i => i !== item)
            } else {
                this.selectedProduk.push(item)
            }
        },
        simpan() {
            if (this.selectedProduk.length == 0) {
                swal.fire('Peringatan', 'Pilih minimal 1 produk', 'warning')
                return
            }
            console.log(this.selectedProduk)
            swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
            this.$emit('refresh')
            closeModal()
        }
    },
    computed: {
        filterRecursive() {
            const includeSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includeSearch(obj[key], search)
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase())
                    })
                }
                return false
            }

            return this.produk.filter(obj => includeSearch(obj, this.search))
        }
    }
}
</script>
<template>
    <div>
        <modalNoSeri :detailSelected="detailSelectedNoSeri" v-if="showModalNoseri" @closeModal="closeModalNoseri" />
        <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">

                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{ detailSelected.so }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{ detailSelected.akn ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ detailSelected.po }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Instansi</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">{{ detailSelected.customer }}</span>
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
                                            <th v-if="!detailSelected.detailOpen"><input type="checkbox" @click="checkedAll"
                                                    :checked="checkAll"></th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th v-if="detailSelected.detailOpen">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="filterRecursive.length > 0">
                                        <template v-for="paket in filterRecursive">
                                            <tr class="table-dark">
                                                <td colspan="100%">
                                                    {{ paket.nama }} <br>
                                                    <span class="badge badge-light">Belum Transfer: {{ paket.jumlah_sisa }}
                                                        ({{ paket.
                                                            persentase_belum }}%)</span>
                                                    <span class="badge badge-warning">
                                                        Sudah Transfer: {{ paket.jumlah_gudang }} ({{
                                                            paket.persentase_gudang
                                                        }}%)
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-for="item in paket.item" :key="item.id">
                                                <td v-if="!detailSelected.detailOpen">
                                                    <input type="checkbox" v-if="!item.status" @click="checkOne(item)"
                                                        :checked="selectedProduk.find(i => i.id === item.id)">

                                                </td>
                                                <td>
                                                    <v-select :options="item.variasi" v-model="item.variasiSelected"
                                                        v-if="!detailSelected.detailOpen"></v-select>
                                                    <span v-else>{{ item.variasiSelected.label }}</span>
                                                </td>
                                                <td>{{ item.jumlah }}</td>
                                                <td>
                                                    <span v-if="item.status" class="badge badge-success">Sudah
                                                        Diinput</span>
                                                    <span v-else class="badge badge-danger">Belum Diinput</span>
                                                </td>
                                                <td v-if="detailSelected.detailOpen">
                                                    <button class="btn btn-sm btn-outline-info" v-if="item.status"
                                                        @click="openModalNoseri(item)">
                                                        <i class="fa fa-info-circle"></i>
                                                        Detail No. Seri Produk
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="100%" class="text-center">Data tidak ditemukan</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="!detailSelected.detailOpen">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
