<script>
import pagination from "../../components/pagination.vue";
import Hasil from "../../components/hasil.vue";
import axios from "axios";
import modalchecked from "../kalibrasi/kalibrasi_internal/detail/modalchecked.vue";
export default {
    components: {
        pagination,
        Hasil,
        modalchecked,
    },
    props: ["produk"],
    data() {
        return {
            search: "",
            renderPaginate: [],
            filterHasil: [],
            noseri: [],
            modalChecked: false,
        };
    },
    methods: {
        clickFilterHasil(filter) {
            if (this.filterHasil.includes(filter)) {
                this.filterHasil = this.filterHasil.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterHasil.push(filter);
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
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

            // if (this.produk.noseri.length > this.produk.jumlah) {
            //     this.$swal(
            //         "Peringatan",
            //         "Jumlah nomor seri tidak boleh lebih dari jumlah produk",
            //         "warning"
            //     );
            //     return;
            // }
            this.$emit("simpan", this.produk);
        },
        statusText(text) {
            if (typeof text == "string") {
                text = text.toLowerCase();
            }
            switch (text) {
                case "ok":
                    return "lolos kalibrasi";
                    break;
                case "not_ok":
                    return "tidak lolos kalibrasi";
                    break;
                default:
                    return "belum kalibrasi";
                    break;
            }
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/labs/tf/seri/${this.produk.id}`);
                this.noseri = data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.checkNoSeriCheckAll();
            }
        },
        openModalChecked() {
            this.modalChecked = true;
            $(".modalSeri").modal("hide");
            this.$nextTick(() => {
                $(".modalChecked").modal("show");
            });
        },
        closeModalChecked() {
            this.modalChecked = false;
            $(".modalChecked").modal("hide");
            this.$nextTick(() => {
                $(".modalSeri").modal("show");
            });
        },
        submit(noseri) {
            let noserinotfound = [];

            let noseriarray = noseri.split(/[\n, \t]/);

            noseriarray = noseriarray.filter((item) => item !== "");

            noseriarray = [...new Set(noseriarray)];

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (this.noseri[j].no_seri === noseriarray[i]) {
                        found = true
                        this.selectNoSeri(this.noseri[j])
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }
            if (noserinotfound.length > 0 && noserinotfound !== "") {
                this.$swal('Peringatan', "Nomor seri " +
                    (noserinotfound.length > 1
                        ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                        : noserinotfound.join(", ")) +
                    " tidak ditemukan", 'warning')
            }
        },
    },
    computed: {
        filteredDalamProses() {
            let filtered = [];
            if (this.filterHasil.length > 0) {
                this.filterHasil.forEach((filter) => {
                    filtered = filtered.concat(
                        this.noseri.filter((data) => data.status == filter)
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
                    return hasil.status;
                })
                .filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
        },
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <modalchecked v-if="modalChecked" @close="closeModalChecked" @submit="submit" />
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
                                                            {{
                                                                statusText(status)
                                                            }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                                &nbsp;
                                <button class="btn btn-primary" @click="openModalChecked">Pilih Nomor Seri Via Text</button>
                            </div>
                            <div class="p-2 bd-highlight">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Cari..."
                                    v-model="search"
                                />
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
                                        />
                                    </th>
                                    <th>No Urut</th>
                                    <th>No. Seri</th>
                                    <th>Tanggal Masuk</th>
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
                                                    (item) =>
                                                        item.no_seri == data.no_seri
                                                )
                                            "
                                            @click="selectNoSeri(data)"
                                        />
                                    </td>
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ data.no_seri }}</td>
                                    <td>{{ formatDate(data.tgl_masuk) }}</td>
                                    <td>
                                        <hasil :hasil="data.status" />
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
                        <pagination
                            :filteredDalamProses="filteredDalamProses"
                            @updateFilteredDalamProses="
                                updateFilteredDalamProses
                            "
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
    </div>
</template>
