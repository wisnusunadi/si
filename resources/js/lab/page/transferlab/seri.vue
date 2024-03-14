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
            filterHasil: [],
            noseri: [],
            modalChecked: false,
            headers: [
                {
                    text: "id",
                    value: "id",
                    sortable: false,
                },
                {
                    text: "No Urut",
                    value: "no_urut",
                },
                {
                    text: "No. Seri",
                    value: "no_seri"
                },
                {
                    text: "Tanggal Masuk",
                    value: "tanggal_masuk"
                },
                {
                    text: "Hasil",
                    value: "hasil",
                }
            ],
            noseriSelected: [],
            checkAll: false,
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
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
        },
        selectNoSeri(noseri) {
            if (this.noseriSelected.find((data) => data.no_seri === noseri.no_seri)) {
                this.noseriSelected = this.noseriSelected.filter(
                    (data) => data.no_seri !== noseri.no_seri
                );
            } else {
                if (noseri.is_ready == 1) {
                    this.noseriSelected.push(noseri);
                }
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noseriSelected = this.noseri.filter((data) => data.is_ready == 1);
            } else {
                this.noseriSelected = [];
            }
        },
        simpan() {
            if (this.noseriSelected.length === 0) {
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
            let produk = { ...this.produk, noseri: this.noseriSelected };
            this.$emit("simpan", produk);
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
                const { data } = await axios.get(`/api/labs/tf/seri/${this.produk.id}`).then((res) => res.data);
                this.noseri = data.map((item, index) => {
                    return {
                        no_urut: index + 1,
                        tanggal_masuk: this.formatDate(item.tgl_masuk),
                        ...item,
                    };
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.noseriSelected = JSON.parse(JSON.stringify(this.produk?.noseri));
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

            return filtered;
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
    watch: {
        noseriSelected: {
            handler: function (val) {
                if (val.length === this.noseri.filter((data) => data.is_ready == 1).length) {
                    this.checkAll = true;
                } else {
                    this.checkAll = false;
                }
            },
            deep: true,
        },
    },
};
</script>
<template>
    <div>
        <modalchecked v-if="modalChecked" @close="closeModalChecked" @submit="submit" />
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
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <span class="float-left filter">
                                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <form id="filter_ekat">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Keterangan</label>
                                                </div>
                                                <div class="form-group" v-for="status in getAllStatusUnique"
                                                    :key="status">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" :ref="status"
                                                            :value="status" id="status1"
                                                            @click="clickFilterHasil(status)" />
                                                        <label class="form-check-label text-uppercase" for="status1">
                                                            {{ statusText(status) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                                &nbsp;
                                <button class="btn btn-primary" @click="openModalChecked">Pilih Nomor Seri Via
                                    Text</button>
                            </div>
                            <div class="p-2 bd-highlight">
                                <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                            </div>
                        </div>

                        <data-table :headers="headers" :items="filteredDalamProses" :search="search">
                            <template #header.id>
                                <input type="checkbox" :checked="checkAll" @click="checkedAll" />
                            </template>
                            <template #item.id="{ item }">
                                <div>
                                    <input type="checkbox" v-if="item.is_ready == 1"
                                        :checked="noseriSelected && noseriSelected.find((data) => data.no_seri === item.no_seri)"
                                        @click="selectNoSeri(item)" />
                                </div>
                            </template>
                            <template #item.hasil="{ item }">
                                <div>
                                    <hasil :hasil="item.status" />
                                </div>
                            </template>
                        </data-table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Keluar
                        </button>
                        <button type="button" class="btn btn-primary" @click="simpan">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
