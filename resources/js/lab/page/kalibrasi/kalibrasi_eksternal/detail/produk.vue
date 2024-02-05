<script>
import Pagination from "../../../../components/pagination.vue";
import noseri from "./noseri.vue";
import modalkalibrasi from "./modalkalibrasi";
import axios from "axios";
export default {
    components: {
        Pagination,
        noseri,
        modalkalibrasi,
    },
    props: ["dataTable", "header"],
    data() {
        return {
            search: "",
            renderPaginate: [],
            dataTableSelected: null,
            no_seri_get: [],
            produkSelected: [],
            checkallvalue: false,
            showModal: false,
            checkedAllCondition: false,
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async detail(index, id) {
            this.dataTableSelected = this.dataTable[index];
            this.no_seri_get = [];
            const { data } = await axios.get("/api/labs/kalibrasi/seri/" + id).then((res) => res.data);
            this.no_seri_get = data.map((item, index) => {
                return {
                    no_urut: index + 1,
                    tgl: this.formatDate(item.tgl_masuk),
                    ...item,
                };
            });
        },
        pushnoseri() {
            this.search = "testing";
            this.search = "";
            this.checkedAllCondition = true;   
        },
        checkall() {
            this.checkallvalue = !this.checkallvalue;

            if (!this.checkallvalue) {
                this.produkSelected = [];
            } else {
                // cek dataTable apakah ada object key noseri atau tidak
                this.dataTable.forEach((item) => {
                    if (item.noseri) {
                        // cek apakah produkSelected sudah ada atau belum
                        if (
                            !this.produkSelected.find(
                                (produk) => produk.id == item.id
                            )
                        ) {
                            this.produkSelected.push(item);
                        }
                    }
                });
            }
        },
        checkselect(data) {
            const cekData = () => {
                return data.noseri ? true : false;
            }

            if (this.produkSelected.find((item) => item.id == data.id)) {
                this.produkSelected = this.produkSelected.filter(
                    (item) => item.id != data.id
                );
            } else {
                if (cekData()) {
                    if (!this.produkSelected.find((item) => item.id == data.id)) {
                        this.produkSelected.push(data);
                    }
                }
            }

            if (this.dataTable.length == this.produkSelected.length) {
                this.checkallvalue = true;
            } else {
                this.checkallvalue = false;
            }
        },
        checked(data) {
            if (this.produkSelected.find((item) => item.id == data.id)) {
                return true;
            }
            return false;
        },
        formKalibrasi() {
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalKalibrasi").modal("show");
            });
        },
        refresh() {
            this.$emit("refresh");
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
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
    <div class="row">
        <modalkalibrasi
            @close="showModal = false"
            :productSelected="produkSelected"
            :header="header"
            v-if="showModal"
            @refresh="refresh"
        />
        <div :class="no_seri_get.length > 0 ? 'col-md-8' : 'col-md-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <button
                                class="btn btn-primary"
                                @click="formKalibrasi"
                                v-if="produkSelected.length > 0"
                            >
                                <i class="fas fa-flask"></i>
                                Kalibrasi
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Cari..."
                                    v-model="search"
                                />
                            </div>
                        </div>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th rowspan="2">
                                    <input
                                        type="checkbox"
                                        @click="checkall"
                                        :checked="checkallvalue"
                                        v-if="checkedAllCondition"
                                    />
                                </th>
                                <th rowspan="2">Nama Barang</th>
                                <th rowspan="2">Tipe</th>
                                <th rowspan="2">Jumlah</th>
                                <th colspan="2">Hasil</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>
                                    <i class="fas fa-check"></i>
                                </th>
                                <th>
                                    <i class="fas fa-times"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(data, index) in renderPaginate" :key="index">
                                <td>
                                    <input
                                        v-if="data.noseri"
                                        type="checkbox"
                                        @click="checkselect(data)"
                                        :checked="checked(data)"
                                    />
                                </td>
                                <td>{{ data.nama }}</td>
                                <td>{{ data.tipe }}</td>
                                <td>{{ data.jumlah }}</td>
                                <td>{{ data.jumlah_ok }}</td>
                                <td>{{ data.jumlah_nok }}</td>
                                <td>
                                    <button
                                        class="btn btn-outline-primary"
                                        @click="detail(index, data.id)"
                                    >
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6">Data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination
                        :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses"
                    />
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        * Centang produk dan SN yang akan dikalibrasi untuk
                        mengkalibrasi unit
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4" v-if="no_seri_get.length > 0">
            <noseri
                @checked="pushnoseri"
                :no_seri="no_seri_get"
                :dataTableSelected="dataTableSelected"
            />
        </div>
    </div>
</template>
<style>
.fa-check {
    color: green;
}
.fa-times {
    color: red;
}
</style>
