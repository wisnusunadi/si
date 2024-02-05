<script>
import pagination from "../../../components/pagination.vue";
import seri from "./seri.vue";
import axios from "axios";
export default {
    components: {
        pagination,
        seri,
    },
    props: {
        headerSO: {
            type: Object,
            required: true,
        },
        produk: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
            modalSeri: false,
            produkSelected: null,
            nomorSeri: [],
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $(".modalProduk").modal("hide");
            this.$emit("close");
        },
        detailNoSeri(data) {
            $(".modalProduk").modal("hide");
            this.produkSelected = JSON.parse(JSON.stringify(data));
            this.getNoSeri(data.id);
        },
        async getNoSeri(id) {
            try {
                const { data } = await axios.get(`/api/qc/tf/data_seri/nok/${id}`);
                this.nomorSeri = data;
                this.modalSeri = true;
                this.$nextTick(() => {
                    $(".modalSeri").modal("show");
                });
            } catch (error) {
                console.log(error);
            }
        },
        closeModalSeri() {
            $(".modalSeri").modal("hide");
            this.modalSeri = false;
            $(".modalProduk").modal("show");
        },
        simpanSeri(produk) {
            let index = this.produk.findIndex((data) => data.nama === produk.nama);
            this.produk[index] = JSON.parse(JSON.stringify(produk));
            this.closeModalSeri();
            // make spacing on this.search
            this.search = "&nbsp;";
            setTimeout(() => {
                this.search = "";
            }, 1);
        },
        async simpan() {
            const produk = []

            this.produk.forEach((data) => {
                if (data?.noseri || data.noseri?.length > 0) {
                    produk.push(data)
                } else if (data.jenis != 'produk') {
                    produk.push(data)
                }
            })

            if (produk.length === 0) {
                this.$swal('Error', 'Produk tidak boleh kosong', 'error');
                return;
            }

            try {
                const { data } = await axios.post("/api/qc/tf/nok", {
                    header: this.headerSO,
                    produk
                });
                this.$swal('Berhasil', data.message, 'success');
                this.$emit("refresh");
                this.closeModal();
            } catch (error) {
                this.$swal('Error', error.response.data.message, 'error');
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.produk.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
};
</script>
<template>
    <div>
        <seri v-if="modalSeri" @close="closeModalSeri" :noseri="nomorSeri" :produk="produkSelected" @simpan="simpanSeri" />
        <div class="modal fade modalProduk" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
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
                                        <label for="">Customer</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{
                                                    headerSO.customer
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor SO</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="po">{{
                                                    headerSO.so
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{
                                                    headerSO.no_po
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..."
                                                v-model="search" />
                                        </div>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="renderPaginate.length > 0">
                                        <tr v-for="(
                                                data, index
                                            ) in renderPaginate" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ data.nama }}</td>
                                            <td>{{ data.jumlah }}</td>
                                            <td>
                                                <button @click="detailNoSeri(data)" class="btn btn-primary btn-sm" v-if="data.jenis === 'produk'">
                                                    <i class="fa fa-qrcode"></i>
                                                    Nomor Seri
                                                </button>
                                            </td>
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Keluar
                        </button>
                        <button type="button" class="btn btn-primary" @click="simpan">
                            Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717fe1;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-akn {
    background-color: #df7458;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-po {
    background-color: #85d296;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>
