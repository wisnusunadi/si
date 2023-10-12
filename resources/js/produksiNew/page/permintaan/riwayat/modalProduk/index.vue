<script>
import pagination from '../../../../components/pagination.vue';
import noseri from './noseri.vue';
export default {
    props: ['dataSelected'],
    components: {
        pagination,
        noseri
    },
    data() {
        return {
            search: '',
            renderPaginate: [],
            modalSeri: false,
            dataSeriSelected: null,
        }
    },
    methods: {
        closeModal() {
            $('.modalProduk').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        closeModalSeri() {

        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        produkTransfer(data) {
            let result = 0
            // cek jumlah noseri every produk
            data.produk.forEach((produk) => {
                result += produk.noseri.length
            })
            return result
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataSelected.produk.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <noseri v-if="noseri" :dataSelected="noseri" @closeModal="closeModalSeri" />
        <div class="modal fade modalProduk" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nama Produk</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{ dataSelected.nama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Jumlah Transfer</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{ produkTransfer(dataSelected) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Transfer</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ dateFormat(dataSelected.tgl_transfer) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="renderPaginate.length > 0">
                                        <tr v-for="(data, index) in renderPaginate" :key="index">
                                            <th>{{ index + 1 }}</th>
                                            <td>{{ data.nama }}</td>
                                            <td>{{ data.noseri.length }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-qrcode"></i>
                                                    Detail Nomor Seri
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <pagination :filteredDalamProses="filteredDalamProses"
                                    @updateFilteredDalamProses="updateFilteredDalamProses" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>