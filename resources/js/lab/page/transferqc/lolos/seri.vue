<script>
import axios from 'axios';
import seriviatext from '../../kalibrasi/kalibrasi_internal/detail/modalchecked.vue';
export default {
    props: ["produk"],
    components: {
        seriviatext,
    },
    data() {
        return {
            search: "",
            filterHasil: [],
            headers: [
                {
                    text: "No",
                    value: "no",
                    sortable: false,
                },
                {
                    text: "Nomor Seri",
                    value: "seri",
                },
                {
                    text: "Hasil",
                    value: "hasil",
                }
            ],
            checkAll: false,
            noseri: [],
            noseriSelected: [],
            modalseriviatext: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
        },
        selectNoSeri(noseri) {
            if (this.noseriSelected.find((data) => data.id === noseri.id)) {
                this.noseriSelected = this.noseriSelected.filter(
                    (data) => data.id !== noseri.id
                );
            } else {
                this.noseriSelected.push(noseri);
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noseriSelected = JSON.parse(JSON.stringify(this.noseri));
            } else {
                this.noseriSelected = [];
            }
        },
        simpan() {
            if (this.noseriSelected.length === 0) {
                this.$swal("Peringatan", "Pilih nomor seri terlebih dahulu", "warning");
                return;
            }

            // nomor seri lebih dari jumlah
            if (this.noseriSelected.length > this.produk.jumlah) {
                this.$swal("Peringatan", "Nomor seri lebih dari jumlah", "warning");
                return;
            }

            const produk = {
                ...this.produk,
                noseri: this.noseriSelected,
            };

            this.$emit("simpan", produk);
        },
        hasil_noseri(text) {
            switch (text) {
                case "lolos_pengujian":
                    return {
                        icon: "<i class='fas fa-check text-warning'></i>",
                        text: "Lolos Pengujian",
                    };

                default:
                    return {
                        icon: "<i class='fas fa-check text-pink'></i>",
                        text: "Lolos Kalibrasi",
                    }
            }
        },
        clickFilterHasil(filter) {
            if (!this.produk.noseri) {
                this.produk.noseri = [];
            }

            if (this.filterHasil.includes(filter)) {
                this.filterHasil = this.filterHasil.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterHasil.push(filter);
            }
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/qc/tf/data_seri/ok/${this.produk.id}`);
                this.noseri = data;
                if (this.produk?.noseri) {
                    this.noseriSelected = JSON.parse(JSON.stringify(this.produk.noseri));
                }
            } catch (error) {
                console.log(error);
            }
        },
        openModalSeriViaText() {
            this.modalseriviatext = true;
            this.$nextTick(() => {
                $(".modalChecked").modal("show");
                $(".modalSeri").modal("hide");
            });
        },
        closeModalSeriViaText() {
            this.modalseriviatext = false;
            this.$nextTick(() => {
                $(".modalChecked").modal("hide");
                $(".modalSeri").modal("show");
            });
        },
        submit(noseri) {
            let noserinotfound = []
            let noseriarray = noseri.split(/[\n, \t]/)
            noseriarray = noseriarray.filter((data) => data !== "")
            noseriarray = [...new Set(noseriarray)]
            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (noseriarray[i] === this.noseri[j].seri) {
                        this.selectNoSeri(this.noseri[j])
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            noserinotfound = [...new Set(noserinotfound)]

            if (noserinotfound.length > 0) {
                this.$swal("Peringatan", `Nomor Seri ${noserinotfound.join(", ")} tidak ditemukan`, "warning");
            }
        }
    },
    watch: {
        noseriSelected() {
            if (this.noseriSelected.length === this.noseri.length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    },
    computed: {
        filteredDalamProses() {
            let filtered = [];
            if (this.filterHasil.length > 0) {
                this.filterHasil.forEach((filter) => {
                    filtered = filtered.concat(
                        this.noseri.filter((data) => data.jenis == filter)
                    );
                });
            } else {
                filtered = this.noseri;
            }
            return filtered
        },
        getAllStatusUnique() {
            return this.noseri
                .map((hasil) => {
                    return hasil.jenis;
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
        <seriviatext v-if="modalseriviatext" @close="closeModalSeriViaText" @submit="submit" />
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
                                <button class="btn btn-sm btn-primary" @click="openModalSeriViaText">
                                    Pilih Nomor Seri Via Text
                                </button>
                                <span class="filter">
                                    <button class="btn btn-outline-info btn-sm" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
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
                                                            :value="status" id="status1" @click="
            clickFilterHasil(
                status
            )
            " />
                                                        <label class="form-check-label text-uppercase" for="status1">
                                                            {{ hasil_noseri(status).text }}
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
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                                </div>
                            </div>
                        </div>
                        <data-table :headers="headers" :items="filteredDalamProses">
                            <template #header.no>
                                <input type="checkbox" :checked="checkAll" @click="checkedAll">
                            </template>
                            <template #item.no="{ item }">
                                <input type="checkbox" :checked="noseriSelected && noseriSelected.find(
            (data) => data.id === item.id
        )" @click="selectNoSeri(item)" />
                            </template>
                            <template #item.hasil="{ item }">
                                <span v-html="hasil_noseri(item.jenis).icon"></span>
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
