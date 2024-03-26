<script>
import axios from 'axios'
import seriviatext from '../../penerimaanRework/transfer/modalTransfer/seriviatext.vue'
export default {
    props: ['detail'],
    components: {
        seriviatext
    },
    data() {
        return {
            noseri: [],
            noSeriSelected: [],
            headers: [
                {
                    text: 'No. Seri',
                    value: 'noseri',
                    align: 'text-left'
                },
                {
                    text: 'id',
                    value: 'id',
                    align: 'text-left',
                    sortable: false
                }
            ],
            search: '',
            checkAll: false,
            showmodalviatext: false,
            isScan: false,
            noserinotfound: [],
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        async getData() {
            try {
                this.loading = true
                const { data } = await axios.post('/api/tfp/seri-so', {
                    gdg_barang_jadi_id: this.detail.gbj_id
                })
                this.noseri = data
                if (this.detail?.noseri) {
                    this.noSeriSelected = JSON.parse(JSON.stringify(this.detail.noseri))
                }
            } catch (error) {
                console.error(error)
            } finally {
                this.loading = false
            }
        },
        checkAllData() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.noseri
            } else {
                this.noSeriSelected = []
            }
        },
        checkNoSeri(item) {
            if (this.noSeriSelected.find(i => i.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(i => i.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        },
        simpan() {
            if (this.noSeriSelected.length === 0) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih Nomor Seri Terlebih Dahulu',
                })
                return
            }

            if (this.noSeriSelected.length > this.detail.jumlah) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Nomor Seri yang dipilih tidak boleh lebih dari ${this.detail.jumlah}`,
                })
                return
            }
            const produk = {
                ...this.detail,
                noseri: this.noSeriSelected
            }
            this.$emit('simpan', produk)
            this.closeModal()
        },
        clickModalViaText() {
            this.showmodalviatext = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('hide')
                $('.modalChecked').modal('show')
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
        closeModalViaText() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
            })
        },
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalViaText" @submit="submit" />
        <div class="modal fade modalNoSeri" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih No. Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small>
                            <span class="text-danger">*</span>
                            Nomor seri yang dipilih tidak boleh lebih dari {{ detail.jumlah }}
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
                                    <input type="checkbox" @click="checkAllData" :checked="checkAll">
                                </div>
                            </template>
                            <template #item.id="{ item }">
                                <div>
                                    <input type="checkbox"
                                        :checked="noSeriSelected && noSeriSelected.find(i => i.id === item.id)"
                                        @click="checkNoSeri(item)" />
                                </div>
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
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>