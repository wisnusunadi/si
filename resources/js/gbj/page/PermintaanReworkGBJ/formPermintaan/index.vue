<script>
import pagination from '../../../components/pagination.vue';
import axios from 'axios';
import noseri from './noseri.vue';
export default {
    components: {
        pagination,
        noseri
    },
    props: ['headerData'],
    data() {
        return {
            dataTable: [],
            renderPaginate: [],
            search: '',
            produkSelected: {},
            showSeri: false,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $('.modalPermintaanRework').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        closeModalSeri() {
            $(".modalSeri").modal("hide");
            this.showSeri = false;
            $(".modalPermintaanRework").modal("show");
        },
        async getData() {
            try {
                const { data } = await axios.post('/api/gbj/rw/belum_kirim/produk', this.headerData)
                this.dataTable = data;
            } catch (error) {
                console.log(error);
            }
        },
        selectProduk(data) {
            this.produkSelected = JSON.parse(JSON.stringify(data));
            this.showSeri = true;
            this.$nextTick(() => {
                $(".modalPermintaanRework").modal("hide");
                $(".modalSeri").modal("show");
            });
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
    created() {
        this.getData();
    },
}
</script>
<template>
    <div>
        <noseri :produk="produkSelected" v-if="showSeri" @closeModal="closeModalSeri" />
        <div class="modal fade modalPermintaanRework" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Permintaan Rework</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    <div class="col"> <label for="">Tanggal Mulai</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{ dateFormat(headerData.tgl_mulai) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Tanggal Selesai</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{ dateFormat(headerData.tgl_selesai) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Nama Produk</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ headerData.nama }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col"> <label for="">Jumlah Kebutuhan</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">{{ headerData.belum }}</span>
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
                                            <th>Belum Transfer</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="renderPaginate.length > 0">
                                        <tr v-for="(data, idx) in renderPaginate" :key="idx">
                                            <td>{{ idx + 1 }}</td>
                                            <td>{{ data.nama }}</td>
                                            <td>{{ data.jumlah }}</td>
                                            <td>{{ data.belum }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary"
                                                    @click="selectProduk(data)">
                                                    <i class="fa fa-qrcode"></i>
                                                    Nomor Seri
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
                        <button type="button" class="btn btn-success">Transfer</button>
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

.instansi {
    background-color: #36425E;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>