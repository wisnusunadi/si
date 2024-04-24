<script>
import seriviatext from '../../../../../../gbj/page/penerimaanRework/transfer/modalTransfer/seriviatext.vue';
export default {
    props: ['detail'],
    components: {
        seriviatext
    },
    data() {
        return {
            noseri: [
                {
                    noseri: '123456',
                    id: 1
                },
                {
                    noseri: '123457',
                    id: 2
                },
                {
                    noseri: '123458',
                    id: 3
                }
            ],
            search: '',
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
            noSeriSelected: [],
            checkAll: false,
            showModal: false
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.noseri
            } else {
                this.noSeriSelected = [];
            }
        },
        checkOne(item) {
            if (this.noSeriSelected.find(x => x.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(x => x.id !== item.id);
            } else {
                this.noSeriSelected.push(item);
            }
        },
        openModalNoSeri() {
            this.showModal = true;
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            });
        },
        closeModalNoSeri() {
            this.showModal = false;
            $('.modalNoSeri').modal('show');
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            noseriarray = noseriarray.filter((data) => data !== '')

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (noseriarray[i] == this.noseri[j].noseri) {
                        this.checkOne(this.noseri[j])
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
            }
        },
        simpan() {
            if (this.noSeriSelected.length == 0) {
                swal.fire('Peringatan', 'Pilih nomor seri terlebih dahulu', 'warning')
                return
            }

            if (this.noSeriSelected.length > this.detail.jumlah) {
                swal.fire('Peringatan', 'Jumlah nomor seri yang dipilih melebihi jumlah yang diminta', 'warning')
                return
            }


            let produk = {
                ...this.detail,
                noseri: this.noSeriSelected
            }

            this.$emit('submit', produk)
            this.closeModal()
        }
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length == this.noseri.length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showModal" @close="closeModalNoSeri" @submit="submit" />
        <div class="modal fade modalNoSeri" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Nomor Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small><span class="text-danger">*</span>Nomor seri yang dipilih tidak boleh lebih dari {{
            detail.jumlah }}</small>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <button class="btn btn-primary" @click="openModalNoSeri">Pilih Nomor Seri Via
                                    Text</button>
                            </div>
                            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
                            </div>
                        </div>
                        <data-table :headers="headers" :items="noseri" :search="search">
                            <template #header.id>
                                <input type="checkbox" :checked="checkAll" @click="checkedAll">
                            </template>
                            <template #item.id="{ item }">
                                <div>
                                    <input type="checkbox" :checked="noSeriSelected.find(x => x.id === item.id)"
                                        @click="checkOne(item)">
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
    </div>
</template>