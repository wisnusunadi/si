<script>
function checkAll(checkboxName, condition) {
    var checkboxes = this.$refs[checkboxName];
    var checked = true;
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].value.includes(condition)) {
            if (!checkboxes[i].checked) {
                checked = false;
                break;
            }
        }
    }
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].value.includes(condition)) {
            checkboxes[i].checked = !checked;
        }
    }
}

import pagination from "../../../components/pagination.vue";
export default {
    components: {
        pagination,
    },
    props: ["produk", "noseri"],
    data() {
        return {
            search: "",
            renderPaginate: [],
            filterHasil: [],
            checkModel: false,
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
        },
        selectNoSeri(noseri) {
            if (this.produk.noseri) {
                if (this.produk.noseri.find((data) => data.seri === noseri.seri)) {
                    this.produk.noseri = this.produk.noseri.filter(
                        (data) => data.seri !== noseri.seri
                    );
                    this.$refs.checkAll.checked = false;
                    this.checkModel = false;
                } else {
                    this.produk.noseri.push(noseri);
                }
            } else {
                this.produk.noseri = [];
                this.produk.noseri.push(noseri);
            }

            if (this.produk.noseri.length === this.noseri.length) {
                this.$refs.checkAll.checked = true;
                this.checkModel = true;
            }
        },
        checkAll() {
            if (this.filterHasil.length > 0) {
                if (!this.checkModel) {
                    if (!this.produk.noseri) {
                        this.produk.noseri = [];
                    }
                    this.renderPaginate.forEach((data) => {
                        this.produk.noseri.push(data);
                    });
                    checkAll.call(this, "noseri", "");
                } else {
                    this.renderPaginate.forEach((data) => {
                        this.produk.noseri = this.produk.noseri.filter(
                            (item) => item.seri !== data.seri
                        );
                    });
                }
            } else if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.produk.noseri = [];
                    checkAll.call(this, "noseri", "");
                } else {
                    this.produk.noseri = [];
                    this.noseri.forEach((data) => {
                        this.produk.noseri.push(data);
                    });
                    checkAll.call(this, "noseri", "");
                }
            } else {
                this.produk.noseri = [];
                this.noseri.forEach((data) => {
                    this.produk.noseri.push(data);
                });
                checkAll.call(this, "noseri", "");
            }
        },
        checkNoSeriCheckAll() {
            if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.$refs.checkAll.checked = true;
                    this.checkModel = true;
                } else {
                    this.$refs.checkAll.checked = false;
                    this.checkModel = false;
                }
            } else {
                this.$refs.checkAll.checked = false;
                this.checkModel = false;
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

            // nomor seri lebih dari jumlah
            if (this.produk.noseri.length > this.produk.jumlah) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri lebih dari jumlah produk",
                    "warning"
                );
                return;
            }

            this.$emit("simpan", this.produk);
        },
        hasil_noseri(text) {
            switch (text) {
                case "lolos_pengujian":
                    return "<i class='fas fa-check text-warning'></i>";

                default:
                    return "<i class='fas fa-check text-pink'></i>";
                    break;
            }
        },
        statusText(text) {
            if (typeof text == "string") {
                text = text.toLowerCase();
            }
            switch (text) {
                case "lolos_pengujian":
                    return "Lolos Pengujian";
                    break;
                default:
                    return "Lolos Kalibrasi";
                    break;
            }
        },
        clickFilterHasil(filter) {
            if (!this.produk.noseri){
                this.produk.noseri = [];
            }

            if (this.filterHasil.includes(filter)) {
                this.filterHasil = this.filterHasil.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterHasil.push(filter);
            }

            if (this.filterHasil.length == 0) {
                if (this.noseri.length == this.produk.noseri.length) {
                    this.$refs.checkAll.checked = true;
                } else {
                    this.$refs.checkAll.checked = false;
                }
            }
        },
    },
    computed: {
        filteredDalamProses() {
            let filtered = [];
            if (this.filterHasil.length > 0) {
                this.filterHasil.forEach((filter) => {
                    filtered = filtered.concat(
                        this.noseri.filter((data) => data.hasil == filter)
                    );
                });
            } else {
                filtered = this.noseri;
            }

            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
        getAllStatusUnique() {
            return this.noseri
                .map((hasil) => {
                    return hasil.hasil;
                })
                .filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
        },
    },
    mounted() {
        this.checkNoSeriCheckAll();
    },
};
</script>
<template>
    <div
        class="modal fade modalSeri"
        id="modelId"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <span class="float-left filter">
                                <button
                                    class="btn btn-outline-info"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan"
                                                    >Keterangan</label
                                                >
                                            </div>
                                            <div
                                                class="form-group"
                                                v-for="status in getAllStatusUnique"
                                                :key="status"
                                            >
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        :ref="status"
                                                        :value="status"
                                                        id="status1"
                                                        @click="
                                                            clickFilterHasil(
                                                                status
                                                            )
                                                        "
                                                    />
                                                    <label
                                                        class="form-check-label text-uppercase"
                                                        for="status1"
                                                    >
                                                        {{ statusText(status) }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <input
                                        type="checkbox"
                                        @click="checkAll"
                                        ref="checkAll"
                                        v-model="checkModel"
                                        :checked="checkModel"
                                    />
                                </th>
                                <th>Nomor Seri</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr
                                v-for="(data, index) in renderPaginate"
                                :key="index"
                            >
                                <td>
                                    <input
                                        type="checkbox"
                                        ref="noseri"
                                        :checked="
                                            produk.noseri &&
                                            produk.noseri.find(
                                                (item) => item.seri == data.seri
                                            )
                                        "
                                        @click="selectNoSeri(data)"
                                    />
                                </td>
                                <td>{{ data.seri }}</td>
                                <td>
                                    <span
                                        v-html="hasil_noseri(data.hasil)"
                                    ></span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="2" class="text-center">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination
                        :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses"
                    />
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="simpan"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
