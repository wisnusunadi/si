<script>
import seriviatext from '../../../../penerimaanRework/transfer/modalTransfer/seriviatext.vue';
import axios from 'axios';
export default {
    props: ['detailSelected'],
    components: {
        seriviatext
    },
    data() {
        return {
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
            noseri: [],
            search: '',
            showmodalviatext: false,
            checkAll: false,
            noSeriSelected: [],
            isScan: false,
            noserinotfound: []
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        async getNoSeri() {
            try {
                const { data } = await axios.post('/api/tfp/seri-so', {
                    gdg_barang_jadi_id: this.detailSelected.gbj_id
                })
                this.noseri = data;
            } catch (error) {
                console.log(error);
            }
        },
        checkAllData() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.noseri
            } else {
                this.noSeriSelected = []
            }
        },
        checkNoSeri(item) {
            if (this.noSeriSelected.find(noseri => noseri.id == item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(noseri => noseri.id != item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        },
        clickModalViaText() {
            this.showmodalviatext = true;
            this.$nextTick(() => {
                $('.modalNoSeri').modal('hide')
                $('.modalChecked').modal('show')
            });
        },
        closeModalViaText() {
            this.showmodalviatext = false;
            this.$nextTick(() => {
                $('.modalChecked').modal('hide')
                $('.modalNoSeri').modal('show')
            });
        },
        scanSeri() {
            this.isScan = !this.isScan;
            if (this.isScan) {
                this.$refs.search.focus();
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
            noseriarray = [...new Set(noseriarray)]
            noseriarray = noseriarray.filter(noseri => noseri != '')

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

            if (this.noSeriSelected.length > this.detailSelected.jumlah) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Nomor Seri yang dipilih tidak boleh lebih dari ${this.detailSelected.jumlah}`,
                })
                return
            }

            swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang sudah disimpan tidak dapat diubah",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$emit('submit', this.noSeriSelected)
                    swal.fire(
                        'Berhasil!',
                        'Data berhasil disimpan',
                        'success'
                    )
                    this.closeModal()
                }
            })
        }
    },
    created() {
        this.getNoSeri();
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
                        <h5 class="modal-title">Form No. Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small>
                            <span class="text-danger">*</span>
                            Nomor seri yang dipilih tidak boleh lebih dari {{ detailSelected.jumlah }}
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
                        <data-table :headers="headers" :items="noseri" :search="search">
                            <template #header.id>
                                <div>
                                    <input type="checkbox" @click="checkAllData" :checked="checkAll">
                                </div>
                            </template>
                            <template #item.id="{ item }">
                                <div>
                                    <input type="checkbox" @click="checkNoSeri(item)"
                                        :checked="noSeriSelected && noSeriSelected.find(noseri => noseri.id === item.id)">
                                </div>
                            </template>
                        </data-table>
                        <div v-if="noserinotfound.length > 0">
                            <div class="form-group">
                                <label for="">Nomor Seri Tidak Ditemukan</label>
                                <textarea rows="3" class="form-control" disabled v-model="noserinotfound"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpanSeri">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>