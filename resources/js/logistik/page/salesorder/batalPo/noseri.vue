<script>
import axios from 'axios'
import seriviatext from '../../../../gbj/page/penerimaanRework/transfer/modalTransfer/seriviatext.vue'
export default {
    props: ['detailSelected', 'paket', 'allPaket'],
    components: {
        seriviatext
    },
    data() {
        return {
            noSeriSelected: [],
            noseri: [],
            headers: [
                { text: 'No. Seri', value: 'noseri', align: 'text-left' },
                {
                    text: 'id', value: 'id',
                    align: 'text-left',
                    sortable: false,
                },
            ],
            search: '',
            checkAll: false,
            showmodalviatext: false,
            isScan: false,
            noserinotfound: [],
            loading: false
        }
    },
    methods: {
        async getData() {
            try {
                this.loading = true
                const { data } = await axios.get(`/api/penjualan/batal_po/log/seri/${this.detailSelected.id}`)
                this.noseri = data.map((item, index) => {
                    return {
                        ...item,
                        status: true
                    }
                })
                if (this.detailSelected?.noseri) {
                    this.noSeriSelected = JSON.parse(JSON.stringify(this.detailSelected.noseri))
                }
            } catch (error) {
                console.error(error)
            } finally {
                this.loading = false
            }
        },
        noseriterpakai(item) {
            let found = false
            for (let i = 0; i < this.allPaket.length; i++) {
                for (let j = 0; j < this.allPaket[i].produk.length; j++) {
                    if (this.allPaket[i].produk[j]?.noseri === undefined) {
                        continue
                    }
                    if (this.allPaket[i].produk[j]?.noseri?.find(noseri => noseri.id === item.id)) {
                        if (this.allPaket[i].produk[j].id !== this.detailSelected.id) {
                            found = true
                            break
                        }
                    }
                }
            }
            return found
        },
        closeModal() {
            $('.modalNoSeri').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })

        },
        checkAllData() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.noseri.filter(noseri => !this.noseriterpakai(noseri) && noseri.status)
            } else {
                this.noSeriSelected = []
            }
        },
        checkNoSeri(item) {
            if (this.noSeriSelected.find(noseri => noseri.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(noseri => noseri.id !== item.id)
            } else {
                if (!this.noseriterpakai(item) && item.status) {
                    this.noSeriSelected.push(item)
                }
            }
        },
        clickModalViaText() {
            this.showmodalviatext = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('hide')
                $('.modalChecked').modal('show')
            })
        },
        closeModalViaText() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
            })
        },
        scanSeri() {
            this.isScan = !this.isScan
            if (this.isScan) {
                this.$refs.search.focus()
            }
        },
        enterScan() {
            if (this.isScan) {
                let noseriarray = this.search.split(/[\n, \t]/)
                let noserinotfound = []
                for (let i = 0; i < noseriarray.length; i++) {
                    let found = false
                    for (let j = 0; j < this.noseri.length; j++) {
                        if (noseriarray[i] === this.noseri[j].noseri) {
                            this.checkNoSeri(this.noseri[j])
                            found = true
                            break
                        }
                    }
                    if (!found) {
                        noserinotfound.push(noseriarray[i])
                    }
                }
                this.search = ''
                noserinotfound = [...new Set(noserinotfound)]

                if (noserinotfound.length > 0) {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Nomor Seri ${noserinotfound.join(', ')} Tidak Ditemukan`,
                    })
                }
            }

        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)
            noseriarray = noseriarray.filter((data) => data !== '')
            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (noseriarray[i] === this.noseri[j].noseri) {
                        this.checkNoSeri(this.noseri[j])
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Peringatan', "Nomor seri " +
                    (noserinotfound.length > 1
                        ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                        : noserinotfound.join(", ")) +
                    " tidak ditemukan", 'warning')
                this.noserinotfound = noserinotfound.join('\n')
            }
        },
        simpanSeri() {
            if (this.noSeriSelected.length === 0) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih Nomor Seri Terlebih Dahulu',
                })
                return
            }

            if (this.noSeriSelected.length > this.detailSelected.jumlah_sisa) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Nomor Seri yang dipilih tidak boleh lebih dari ${this.detailSelected.jumlah_sisa}`,
                })
                return
            }

            let paket = { ...this.paket }
            paket.produk.find(produk => produk.id === this.detailSelected.id).noseri = this.noSeriSelected
            this.$emit('submit', paket)
            this.closeModal()
        }
    },
    created() {
        this.getData()
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.noseri.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalViaText" @submit="submit" />
        <div class="modal fade modalNoSeri" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small>
                            <span class="text-danger">*</span>
                            Nomor seri yang dipilih tidak boleh lebih dari {{ detailSelected.jumlah_sisa }}
                        </small>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <button class="btn btn-primary" @click="clickModalViaText">Pilih Nomor Seri Via
                                    Text</button> <br>
                                <div class="custom-control custom-switch my-2">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                        @click="scanSeri" :checked="isScan">
                                    <label class="custom-control-label" for="customSwitch1">Scan Nomor Seri</label>
                                </div>
                            </div>
                            <div class="p-2 bd-highlight">
                                <input type="text" class="form-control" v-model="search" placeholder="Cari..."
                                    ref="search" @keyup.enter="enterScan" />
                            </div>
                        </div>
                        <data-table :items="noseri" :headers="headers" :search="search" v-if="!loading">
                            <template #header.id>
                                <div>
                                    <input type="checkbox" @click="checkAllData" :checked="checkAll"
                                        v-if="detailSelected.jumlah_tf != detailSelected.jumlah">
                                </div>
                            </template>
                            <template #item.id="{ item }">
                                <div v-if="item.status">
                                    <div v-if="!noseriterpakai(item)">
                                        <input type="checkbox" @click="checkNoSeri(item)"
                                            :checked="noSeriSelected && noSeriSelected.find(noseri => noseri.id === item.id)">
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-info">No Seri Terpakai</span>
                                    </div>
                                </div>
                                <div></div>
                            </template>
                        </data-table>
                        <div v-else class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>
                        <div v-if="noserinotfound.length > 0">
                            <div class="form-group">
                                <label for="">Nomor Seri Tidak Ditemukan</label>
                                <textarea class="form-control" rows="3" disabled v-model="noserinotfound"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" @click="simpanSeri"
                            v-if="detailSelected.jumlah_tf != detailSelected.jumlah">Simpan</button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>