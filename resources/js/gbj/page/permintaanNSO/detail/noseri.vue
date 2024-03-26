<script>
import axios from 'axios'
export default {
    props: ['detail'],
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
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
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
                    <data-table :items="noseri" :headers="headers" :search="search">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>