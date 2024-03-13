<script>
import Pagination from "../../../../components/pagination.vue";
import modalkalibrasi from "./modalkalibrasi";
import axios from "axios";
import modalchecked from "./modalchecked.vue";
export default {
    components: {
        Pagination,
        modalkalibrasi,
        modalchecked,
    },
    props: ["produk", "header"],
    data() {
        return {
            search: "",
            renderPaginate: [],
            dataTableSelected: null,
            dataTable: JSON.parse(JSON.stringify(this.produk)),
            no_seri_get: [],
            produkSelected: [],
            checkallvalue: false,
            showModal: false,
            checkedAllCondition: false,
            // noseri
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No Urut',
                    value: 'no_urut'
                },
                {
                    text: 'No Seri',
                    value: 'no_seri'
                },
                {
                    text: 'Tgl Masuk',
                    value: 'tgl'
                },
                {
                    text: 'Hasil',
                    value: 'status',
                    sortable: false,
                }
            ],
            loading: false,
            checkedAllNoSeri: false,
            noSeriSelected: [],
            filterHasil: [],
            searchNoSeri: "",
            showModalChecked: false,
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async detail(index, id) {
            try {
                this.loading = true;
                this.dataTableSelected = this.dataTable[index];
                this.noSeriSelected = [];
                if (this.dataTableSelected?.noseri) {
                    this.noSeriSelected = this.dataTableSelected.noseri;
                }
                this.no_seri_get = [];
                const { data } = await axios.get("/api/labs/kalibrasi/seri/" + id).then((res) => res.data);
                this.no_seri_get = data.map((item, index) => {
                    return {
                        no_urut: index + 1,
                        tgl: this.formatDate(item.tgl_masuk),
                        ...item,
                    };
                });
                if (this.noSeriSelected.length == this.no_seri_get.length) {
                    this.checkedAllNoSeri = true;
                } else {
                    this.checkedAllNoSeri = false;
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
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
        },
        // noseri
        checkAllNoSeri() {
            this.checkedAllNoSeri = !this.checkedAllNoSeri;
            if (this.checkedAllNoSeri) {
                this.noSeriSelected = this.no_seri_get.filter(
                    (item) => item.status == "belum"
                );
            } else {
                this.noSeriSelected = [];
            }
            this.search = "&";
            this.$nextTick(() => {
                this.search = "";
            });
        },
        checkedNoSeri(item) {
            if (this.noSeriSelected.find((x) => x.id == item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((x) => x.id != item.id);
            } else {
                this.noSeriSelected.push(item);
            }
            this.search = "&";
            this.$nextTick(() => {
                this.search = "";
            });
        },
        clickFilterHasil(filter) {
            if (this.filterHasil.includes(filter)) {
                this.filterHasil = this.filterHasil.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterHasil.push(filter);
            }
        },
        statusText(status) {
            switch (status) {
                case 'ok':
                    return {
                        text: 'lolos kalibrasi',
                        class: 'fas fa-check-circle text-success'
                    }
                case 'not_ok':
                    return {
                        text: 'tidak lolos kalibrasi',
                        class: 'fas fa-times-circle text-danger'
                    }
                default:
                    return {
                        text: 'belum kalibrasi',
                        class: 'fas fa-question-circle text-warning'
                    }
            }
        },
        showNoSeriText() {
            this.showModalChecked = true;
            this.$nextTick(() => {
                $(".modalChecked").modal("show");
            });
        },
        submit(noseri) {
            let noserinotfound = []
            let noseriarray = noseri.split(/[\n, \t]/);
            noseriarray = noseriarray.filter((item) => item != "");
            noseriarray = [...new Set(noseriarray)]
            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.no_seri_get.filter((item) => item.status == "belum").length; j++) {
                    if (noseriarray[i] == this.no_seri_get.filter((item) => item.status == "belum")[j].no_seri) {
                        this.checkedNoSeri(this.no_seri_get.filter((item) => item.status == "belum")[j])
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Peringatan', `No Seri ${noserinotfound.join(', ')} tidak ditemukan atau sudah diuji`, 'warning')
            }
        },
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
        getAllStatusUnique() {
            return [...new Set(this.no_seri_get.map((item) => item.status))];
        },
        filteredNoSeri() {
            let filtered = this.no_seri_get;

            if (this.filterHasil.length > 0) {
                filtered = filtered.filter((item) => {
                    return this.filterHasil.includes(item.status);
                });
            }

            return filtered;
        },
    },
    watch: {
        search() {
            // jika di datatable ada object key noseri dan bernilai array lebih dari 0 maka push ke produkSelected
            if (this.dataTableSelected?.noseri.length > 0) {
                if (!this.produkSelected.find((item) => item.id == this.dataTableSelected.id)) {
                    this.produkSelected.push(this.dataTableSelected);
                }
            } else {
                this.produkSelected = this.produkSelected.filter((item) => item.id != this.dataTableSelected.id);
            }
        },
        produkSelected() {
            if (this.produkSelected.length == this.dataTable.length) {
                this.checkallvalue = true;
                this.checkedAllCondition = true;
            } else {
                this.checkallvalue = false;
                this.checkedAllCondition = false;
            }
        },
        noSeriSelected() {
            if (this.noSeriSelected.length == this.no_seri_get.filter((item) => item.status == "belum").length) {
                this.checkedAllNoSeri = true;
            } else {
                this.checkedAllNoSeri = false;
            }

            this.dataTableSelected.noseri = this.noSeriSelected;
        }
    }
};
</script>
<template>
    <div class="row">
        <modalkalibrasi @close="showModal = false" :productSelected="produkSelected" :header="header" v-if="showModal"
            @refresh="refresh" />
        <modalchecked v-if="showModalChecked" @close="showModalChecked = false" @submit="submit" />
        <div :class="no_seri_get.length > 0 ? 'col-7' : 'col'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <button class="btn btn-primary" @click="formKalibrasi" v-if="produkSelected.length > 0">
                                <i class="fas fa-flask"></i>
                                Kalibrasi
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                            </div>
                        </div>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th rowspan="2">
                                    <input type="checkbox" @click="checkall" :checked="checkallvalue"
                                        v-if="checkedAllCondition" />
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
                                    <input v-if="data.noseri && data.noseri.length > 0" type="checkbox"
                                        @click="checkselect(data)" :checked="checked(data)" />
                                </td>
                                <td>{{ data.nama }}</td>
                                <td>{{ data.tipe }}</td>
                                <td>{{ data.jumlah }}</td>
                                <td>{{ data.jumlah_ok }}</td>
                                <td>{{ data.jumlah_nok }}</td>
                                <td>
                                    <button class="btn btn-outline-primary" @click="detail(index, data.id)"
                                        :disabled="loading">
                                        <i class="fas fa-eye"></i>
                                        {{ loading ? 'Loading...' : 'Detail' }}
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
                    <pagination :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        * Centang produk dan SN yang akan dikalibrasi untuk
                        mengkalibrasi unit
                    </small>
                </div>
            </div>
        </div>
        <div class="col-5" v-if="no_seri_get.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <span class="filter">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <button class="btn btn-outline-info">
                                        <i class="fas fa-filter"></i>
                                        Filter
                                    </button>
                                </a>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3 font-weight-normal">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Keterangan</label>
                                            </div>
                                            <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" :ref="status"
                                                        :value="status" id="status1" @click="
            clickFilterHasil(status)
            " />
                                                    <label class="form-check-label text-uppercase" for="status1">
                                                        {{ statusText(status).text }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-primary" @click="showNoSeriText"
                                v-if="$route.params.jenis == 'dalamproses'">
                                Pilih No Seri Via Text
                            </button>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchNoSeri" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="filteredNoSeri" :search="searchNoSeri">
                        <template #header.id>
                            <input type="checkbox" @click="checkAllNoSeri" :checked="checkedAllNoSeri"
                                v-if="$route.params.jenis == 'dalamproses'">
                            <span v-else></span>
                        </template>

                        <template #item.id="{ item }">
                            <div>
                                <input type="checkbox" @click="checkedNoSeri(item)" v-if="item.status == 'belum'"
                                    :checked="noSeriSelected && noSeriSelected.find((x) => x.id == item.id)" />
                            </div>
                        </template>

                        <template #item.status="{ item }">
                            <i :class="statusText(item.status).class"></i>
                        </template>
                    </data-table>
                </div>
            </div>
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
