<script>
import axios from "axios";
import seriviatext from "./seriviatext.vue";
export default {
    components: {
        seriviatext,
    },
    props: ['produk'],
    data() {
        return {
            search: '',
            noseri: [],
            loading: false,
            showmodalviatext: false,
            isScan: false,
            noseritidakditemukan: [],
            noSeriSelected: [],
            checkedAll: false,
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    align: 'text-left',
                    sortable: false
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                    align: 'text-left'
                },
                {
                    text: 'Variasi',
                    value: 'variasi',
                    align: 'text-left'
                }
            ]
        }
    },
    methods: {
        closeModal() {
            this.$emit("closeModal");
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((x) => x.id === noseri.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((x) => x.id !== noseri.id);
            } else {
                this.noSeriSelected.push(noseri);
            }
        },
        checkAll() {
            this.checkedAll = !this.checkedAll;
            if (this.checkedAll) {
                this.noSeriSelected = this.noseri;
            } else {
                this.noSeriSelected = [];
            }
        },
        simpan() {
            if (this.noSeriSelected.length === 0) {
                this.$swal(
                    "Peringatan",
                    "Pilih nomor seri terlebih dahulu",
                    "warning"
                );
                return;
            }

            if (this.noSeriSelected.length > this.produk.belum) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri tidak boleh lebih dari jumlah produk yang belum dikirim",
                    "warning"
                );
                return;
            }
            const produk = {
                ...this.produk,
                noseri: this.noSeriSelected,
            };
            this.$emit("simpan", produk);
        },
        async getData() {
            try {
                this.loading = true;
                const { data } = await axios.get(`/api/gbj/rw/belum_kirim/seri/${this.produk.produk_id}`);
                this.noseri = data;
                this.loading = false;
                if (this.produk?.noseri) {
                    this.noSeriSelected = this.produk.noseri;
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        showSeriText() {
            this.showmodalviatext = true
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
                $('.modalSeri').modal('hide')
            })
        },
        closeModalViaText() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        submit(noseri) {

            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            // remove empty string
            noseriarray = noseriarray.filter((x) => x != "")

            // remove duplicate
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

            if (noserinotfound.length > 0 && noserinotfound != "") {
                this.$swal('Peringatan', "Nomor seri " +
                    (noserinotfound.length > 1
                        ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                        : noserinotfound.join(", ")) +
                    " tidak ditemukan", 'warning')
                this.noseritidakditemukan = noserinotfound

                // this.$swal({
                //     title: "Peringatan",
                //     // tampilkan jumlah dan nomor seri yang tidak ditemukan jika length > 5 maka tampilkan ... jika tidak tampilkan semua
                //     text:
                //         "Nomor seri " +
                //         (noserinotfound.length > 1
                //             ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                //             : noserinotfound.join(", ")) +
                //         " tidak ditemukan",
                //     icon: "warning",
                //     showCancelButton: true,
                //     confirmButtonColor: "#3085d6",
                //     cancelButtonColor: "#d33",
                //     confirmButtonText: "Copy nomor seri yang tidak ditemukan",
                //     cancelButtonText: "Tidak",
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         try {
                //             navigator.clipboard.writeText(noserinotfound.join("\n"))
                //             this.$swal({
                //                 title: "Berhasil!",
                //                 text: "Nomor seri berhasil dicopy",
                //                 icon: "success",
                //             })
                //         } catch (error) {
                //             this.$swal({
                //                 title: "Gagal!",
                //                 text: "Nomor seri gagal dicopy",
                //                 icon: "error",
                //             })
                //         }
                //     }
                // })
            }
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

                this.search = "";

                noserinotfound = [...new Set(noserinotfound)]

                if (noserinotfound.length > 0 && noserinotfound != "") {
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
        }
    },
    created() {
        this.getData();
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.noseri.length) {
                this.checkedAll = true;
            } else {
                this.checkedAll = false;
            }
        },
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalViaText" @submit="submit" />
        <div class="modal fade modalSeri" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight"><button class="btn btn-primary"
                                        @click="showSeriText">Pilih No Seri Via
                                        Text</button>
                                    <br>
                                    <div class="custom-control custom-switch my-3">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                            :checked="isScan" @click="scanSeri">
                                        <label class="custom-control-label" for="customSwitch1">Scan Nomor Seri
                                        </label>
                                    </div>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <input type="text" class="form-control" v-model="search" placeholder="Cari No Seri"
                                        ref="search" @keyup.enter="autoSelect" />
                                </div>
                            </div>
                            <data-table :headers="headers" :items="noseri" :search="search">
                                <template #header.id>
                                    <div>
                                        <input type="checkbox" @click="checkAll" :checked="checkedAll" />
                                    </div>
                                </template>
                                <template #item.id="{ item }">
                                    <div>
                                        <input type="checkbox"
                                            :checked="noSeriSelected && noSeriSelected.find((x) => x.id === item.id)"
                                            @click="selectNoSeri(item)" />
                                    </div>
                                </template>
                            </data-table>
                            <div class="form-group" v-if="noseritidakditemukan.length > 0">
                                <label for="">No Seri Tidak Ditemukan</label>
                                <textarea class="form-control" rows="3"
                                    readonly>{{ noseritidakditemukan.join("\n") }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>