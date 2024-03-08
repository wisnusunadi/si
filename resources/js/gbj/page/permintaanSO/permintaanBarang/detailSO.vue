<script>
import axios from 'axios'
import pagination from '../../../components/pagination.vue'
export default {
    components: {
        pagination,
    },
    props: ['detailSelected'],
    data() {
        return {
            produk: [],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailSO').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        persentase(jmlPerItem, jmlTotal) {
            let item = parseInt(jmlPerItem)
            let total = parseInt(jmlTotal)
            return Math.round((item / total) * 100)
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/tfp/detail-so/${this.detailSelected.id}`)
                this.produk = data.map(paket => {
                    return {
                        ...paket,
                        persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                        persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                        item: paket.item.map(item => {
                            return {
                                ...item,
                                persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                                persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        this.getData()
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
    <div class="modal fade modalDetailSO" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                                            <span id="po">{{ detailSelected.no_po }}</span>
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
                                    <input type="text" class="form-control" v-model="search">
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
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
                        paket.persentase_sudah
                    }}%)
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-for="item in paket.item" :key="item.id">
                                            <td>
                                                {{ item.variasiSelected.label }}
                                            </td>
                                            <td>{{ item.jumlah }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="100%">Data tidak ditemukan</td>
                                    </tr>
                                </tbody>
                            </table>
                            <pagination :filteredDalamProses="filterRecursive"
                                @updateFilteredDalamProses="updateFilteredDalamProses" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>