<script>
import Table from './table.vue';
import pagination from '../../../components/pagination.vue';
export default {
    components: {
        Table,
        pagination,
    },
    props: ['headerData'],
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    nama_produk: 'Produk 1',
                },
                {
                    id: 2,
                    nama_produk: 'Produk 2',
                },
                {
                    id: 3,
                    nama_produk: 'Produk 3',
                }
            ],
            renderPaginate: [],
            search: '',
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
}
</script>
<template>
    <div class="modal fade modalPermintaanRework"  data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                            <span id="so">{{ dateFormat(headerData.tanggal_mulai) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col"> <label for="">Tanggal Selesai</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <span id="akn">{{ dateFormat(headerData.tanggal_mulai) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col"> <label for="">Nama Produk</label>
                                    <div class="card nomor-po">
                                        <div class="card-body">
                                            <span id="po">{{ headerData.nama_produk }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col"> <label for="">Jumlah Kebutuhan</label>
                                    <div class="card instansi">
                                        <div class="card-body">
                                            <span id="instansi">{{ headerData.jumlah_belum_selesai }}</span>
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
                            <Table :dataTable="renderPaginate" :jumlah="headerData.jumlah_belum_selesai" />
                            <pagination :filteredDalamProses="filteredDalamProses"
                                @updateFilteredDalamProses="updateFilteredDalamProses" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
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