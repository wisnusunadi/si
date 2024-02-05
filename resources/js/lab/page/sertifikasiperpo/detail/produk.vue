<script>
import Pagination from '../../../components/pagination.vue'
import modalpage from "../../../components/modalpage.vue";
export default {
    components: {
        Pagination,
        modalpage,
    },
    props: ['dataTable'],
    data() {
        return {
            search: '',
            renderPaginate: [],
            modal: false,
            dataCetak: null,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data
        },
        detail(id) {
            this.$router.push({ name: 'detailsertifikasipernoseri', params: { id: id, history: this.$route.path } })
        },
        cetakSertifikat(id, ttd) {
            this.modal = true;
            this.dataCetak = {
                id,
                ttd,
                jenis: 'produk',
            };
            this.$nextTick(() => {
                $(".modalPage").modal("show");
            });
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    }
}
</script>
<template>
    <div class="row">
        <modalpage v-if="modal" @closeModal="modal = false" :dataCetak="dataCetak" />
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                            <div class="mr-auto p-2 bd-highlight">
                            </div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                                </div>
                            </div>
                        </div>
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Barang</th>
                                    <th rowspan="2">Tipe Barang</th>
                                    <th colspan="2">Jumlah</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th>OK</th>
                                    <th>Tidak OK</th>
                                </tr>
                            </thead>
                            <tbody v-if="renderPaginate.length > 0">
                                <tr v-for="(data, index) in renderPaginate" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ data.nama }}</td>
                                    <td>{{ data.tipe }}</td>
                                    <td>{{ data.jumlah_ok }}</td>
                                    <td>{{ data.jumlah_nok }}</td>
                                                        <td>
                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                                aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <a class="dropdown-item" @click="detail(data.id)">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </a>
                                <button class="dropdown-item" type="button" @click="cetakSertifikat(data.id, ttd = false)">
                                    <i class="fas fa-file"></i>
                                    Sertifikasi
                                </button>
                                <button class="dropdown-item" type="button" @click="cetakSertifikat(data.id, ttd = true)">
                                    <i class="fas fa-file"></i>
                                    Sertifikasi + TTD
                                </button>
                            </div>
                        </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4">Data tidak ditemukan</td>
                                </tr>
                            </tbody>
                        </table>
                <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses"/>
                </div>
            </div>
        </div>
    </div>
</template>