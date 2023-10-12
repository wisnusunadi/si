<script>
import pagination from "../../../components/pagination.vue";
import axios from "axios";
export default {
    components: {
        pagination,
    },
    props: ['produk'],
    data() {
        return {
            search: '',
            renderPaginate: [],
            noseri: [],
            loading: false,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            this.$emit("closeModal");
        },
        selectNoSeri(noseri) {
            if (this.produk.noseri) {
                if (this.produk.noseri.find((data) => data.no_seri === noseri.no_seri)) {
                    this.produk.noseri = this.produk.noseri.filter(
                        (data) => data.no_seri !== noseri.no_seri
                    );
                    this.$refs.checkAll.checked = false;
                } else {
                    this.produk.noseri.push(noseri);
                }
            } else {
                this.produk.noseri = [];
                this.produk.noseri.push(noseri);
            }

            if (this.produk.noseri.length === this.noseri.length) {
                this.$refs.checkAll.checked = true;
            }
        },
        checkAll() {
            if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.produk.noseri = [];
                    this.$refs.noseri.forEach((data) => {
                        data.checked = false;
                    });
                } else {
                    this.produk.noseri = [];
                    this.noseri.forEach((data) => {
                        this.produk.noseri.push(data);
                    });
                    this.$refs.noseri.find((data) => {
                        data.checked = true;
                    });
                }
            } else {
                this.produk.noseri = [];
                this.noseri.forEach((data) => {
                    this.produk.noseri.push(data);
                });
                this.$refs.noseri.forEach((data) => {
                    data.checked = true;
                });
            }
        },
        checkNoSeriCheckAll() {
            if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.$refs.checkAll.checked = true;
                } else {
                    this.$refs.checkAll.checked = false;
                }
            } else {
                this.$refs.checkAll.checked = false;
            }
        },
        simpan() {
            if (!this.produk.noseri || this.produk.noseri.length === 0) {
                this.$swal(
                    "Peringatan",
                    "Pilih nomor seri terlebih dahulu",
                    "warning"
                );
                return;
            }

            if (this.produk.noseri.length > this.produk.belum) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri tidak boleh lebih dari jumlah produk",
                    "warning"
                );
                return;
            }
            this.$emit("simpan", this.produk);
        },
        async getData() {
            try {
                this.loading = true;
                const { data } = await axios.get(`/api/gbj/rw/belum_kirim/seri/${this.produk.produk_id}`);
                this.noseri = data;
                this.loading = false;
            } catch (error) {
                console.log(error);
            } finally {
                this.checkNoSeriCheckAll();
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.noseri.filter((data) => {
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
    <div class="modal fade modalSeri" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="spinner-border" role="status" v-if="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <table class="table" v-else>
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" @click="checkAll" ref="checkAll" />
                                </th>
                                <th>No. Seri</th>
                                <th>Variasi</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(data, index) in renderPaginate" :key="index">
                                <td>
                                    <input type="checkbox" ref="noseri" :checked="produk.noseri &&
                                        produk.noseri.find(
                                            (item) =>
                                                item.no_seri == data.no_seri
                                        )
                                        " @click="selectNoSeri(data)" />
                                </td>
                                <td>{{ data.noseri }}</td>
                                <td>{{ data.variasi }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5" class="text-center">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses
                        " />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>