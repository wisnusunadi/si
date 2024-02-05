<script>
import axios from 'axios'
import seriviatext from '../../penerimaanRework/transfer/modalTransfer/seriviatext.vue'
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
            isScan: false
        }
    },
    methods: {
        async getData() {
            const { data } = await axios.post('/api/tfp/seri-so', {
                gdg_barang_jadi_id: 67
            })
            this.noseri = data
            if (this.detailSelected?.noseri) {
                this.noSeriSelected = JSON.parse(JSON.stringify(this.detailSelected.noseri))
            }
        },
        noseriterpakai(item) {
            let found = false
            for (let i = 0; i < this.allPaket.length; i++) {
                for (let j = 0; j < this.allPaket[i].produk.length; j++) {
                    if (this.allPaket[i].produk[j].noseri === undefined) {
                        continue
                    }
                    if (this.allPaket[i].produk[j].noseri.find(noseri => noseri.id === item.id)) {
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
                this.noSeriSelected = this.noseri.map(item => item)
            } else {
                this.noSeriSelected = []
            }
        },
        checkNoSeri(item) {
            if (this.noSeriSelected.find(noseri => noseri.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(noseri => noseri.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
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
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Nomor Seri ${noserinotfound.join(', ')} Tidak Ditemukan`,
                })
            }
        },
        simpanSeri() {
            if (this.noSeriSelected.length > this.detailSelected.jml) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Nomor Seri yang dipilih tidak boleh lebih dari ${this.detailSelected.jml}`,
                })
                return
            }

            let paket = { ...this.paket }
            paket.produk.find(item => item.id === this.detailSelected.id).noseri = this.noSeriSelected
            this.$emit('submit', paket)
            this.closeModal()
        }
    },
    created() {
        this.getData()
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalViaText" @submit="submit" />
        <div class="modal fade modalNoSeri" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                            Nomor seri yang dipilih tidak boleh lebih dari {{ detailSelected.jml }}
                        </small>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <button class="btn btn-primary" @click="clickModalViaText">Pilih Nomor Seri Via
                                    Text</button> <br>
                                <div class="custom-control custom-switch my-2">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" @click="scanSeri"
                                        :checked="isScan">
                                    <label class="custom-control-label" for="customSwitch1">Scan Nomor Seri</label>
                                </div>
                            </div>
                            <div class="p-2 bd-highlight">
                                <input type="text" class="form-control" v-model="search" placeholder="Cari..." ref="search"
                                    @keyup.enter="enterScan" />
                            </div>
                        </div>
                        <data-table :items="noseri" :headers="headers" :search="search">
                            <template #header.id>
                                <div>
                                    <input type="checkbox" @click="checkAllData" :checked="checkAll">
                                </div>
                            </template>
                            <template #item.id="{ item }">
                                <div v-if="!noseriterpakai(item)">
                                    <input type="checkbox" @click="checkNoSeri(item)"
                                        :checked="noSeriSelected && noSeriSelected.find(noseri => noseri.id === item.id)">
                                </div>
                                <div v-else>
                                    <span class="badge badge-info">No Seri Terpakai</span>
                                </div>
                            </template>
                        </data-table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" @click="simpanSeri">Simpan</button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>