<script>
import axios from 'axios';
import seriviatext from '../kalibrasi/kalibrasi_internal/detail/modalchecked.vue'
export default {
    props: ["produk"],
    components: {
        seriviatext
    },
    data() {
        return {
            search: "",
            noseri: [],
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'No. Seri',
                    value: 'noseri'
                },
            ],
            checkedAll: false,
            isScan: false,
            showmodalviatext: false,
            loading: false,
            noSeriSelected: []
        };
    },
    methods: {
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((data) => data.id === noseri.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(
                    (data) => data.id !== noseri.id
                );
            } else {
                this.noSeriSelected.push(noseri);
            }
        },
        checkAll() {
            if (this.checkedAll) {
                this.noSeriSelected = this.noseri;
            } else {
                this.noSeriSelected = [];
            }
        },
        simpan() {
            if (
                this.noSeriSelected.length === 0
            ) {
                this.$swal(
                    "Peringatan",
                    "Pilih nomor seri terlebih dahulu",
                    "warning"
                );
                return;
            }

            if (this.noSeriSelected.length > this.produk.jumlah) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri tidak boleh lebih dari jumlah produk",
                    "warning"
                );
                return;
            }

            if (this.noSeriSelected.length !== this.produk.jumlah) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri tidak boleh kurang dari jumlah produk",
                    "warning"
                );
                return;
            }
            const produk = {
                ...this.produk,
                noseri: this.noSeriSelected,
            }
            this.$emit("simpan", produk);
        },
        autoSelect() {
            if (this.isScan) {

                let noserinotfound = []

                let noseriarray = this.search.split(/[\n, \t]/)
                for (let i = 0; i < noseriarray.length; i++) {
                    let found = false
                    for (let j = 0; j < this.noseri.length; j++) {
                        if (this.noseri[j].noseri == noseriarray[i]) {
                            this.selectNoSeri(this.noseri[j])
                            found = true
                            break
                        }
                    }
                    if (!found) {
                        noserinotfound.push(noseriarray[i])
                    }
                }

                this.search = ""

                noserinotfound = [...new Set(noserinotfound)]

                if (noserinotfound.length > 0) {
                    this.$swal(
                        "Peringatan",
                        "Nomor seri " + noserinotfound.join(", ") + " tidak ditemukan",
                        "warning"
                    );
                }
            }
        },
        scanSeri() {
            this.isScan = !this.isScan
            this.search = ""
            this.$nextTick(() => {
                this.$refs.search.focus()
            })
        },
        showSeriText() {
            this.showmodalviatext = true
            this.$nextTick(() => {
                $(".modalSeri").modal("hide");
                $(".modalChecked").modal("show");
            });
        },
        closeModalViaText() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $(".modalSeri").modal("show");
                $(".modalChecked").modal("hide");
            });
        },
        submit(noseri) {
            this.loading = true

            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            noseriarray = noseriarray.filter((data) => data !== "")

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (this.noseri[j].noseri == noseriarray[i]) {
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

            if (noserinotfound.length > 0 && noserinotfound !== "") {
                this.$swal('Peringatan', "Nomor seri " +
                    (noserinotfound.length > 1
                        ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                        : noserinotfound.join(", ")) +
                    " tidak ditemukan", 'warning')
            }
            this.$nextTick(() => {
                this.loading = false
            })
        },
        async getData() {
            try {
                if (this.produk?.noseri) {
                    this.noSeriSelected = JSON.parse(JSON.stringify(this.produk.noseri))
                }
                this.loading = true
                const id = this.produk.gbj_id;
                const { data } = await axios.post(`/api/tfp/seri-so`, {
                    gdg_barang_jadi_id: id,
                }).then((res) => res.data);
                this.noseri = data;
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false
            }
        }
    },
    created() {
        this.getData();
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length == this.noseri.length) {
                this.checkedAll = true
            } else {
                this.checkedAll = false
            }
        }
    }
};
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalViaText" @submit="submit" />
        <div class="modal fade modalSeri" id="modelId" data-backdrop="static" data-keyboard="false"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
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
                        <div v-else>
                            <small class="text-muted"><span class="text-danger">*</span>Jumlah No Seri yang dipilih tidak
                                boleh
                                lebih dan kurang dari {{ produk.jumlah }}</small>
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <button class="btn btn-primary" @click="showSeriText">Pilih Nomor Seri Via Text</button>
                                    <br>
                                    <div class="custom-control custom-switch my-3">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                            :checked="isScan" @click="scanSeri">
                                        <label class="custom-control-label" for="customSwitch1">Scan Nomor Seri
                                        </label>
                                    </div>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <input type="text" class="form-control" ref="search" placeholder="Cari..."
                                        v-model="search" @keyup.enter="autoSelect" />
                                </div>
                            </div>
                            <data-table :headers="headers" :items="noseri" :search="search">
                                <template #header.id>
                                    <input type="checkbox" @click="checkAll" :checked="checkedAll" />
                                </template>
                                <template #item.id="{ item }">
                                    <input type="checkbox" :checked="noSeriSelected.find((data) => data.id === item.id)
                                        " @click="selectNoSeri(item)" />
                                </template>

                            </data-table>
                        </div>
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
