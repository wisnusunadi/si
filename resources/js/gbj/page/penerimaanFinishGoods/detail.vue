<script>
import seriviatext from '../penerimaanRework/transfer/modalTransfer/seriviatext.vue'
import modalLayout from './modalLayout.vue'
export default {
    props: ['detail'],
    components: {
        modalLayout,
        seriviatext
    },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    align: 'text-left',
                    sortable: false
                },
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                    align: 'text-left'
                },
                {
                    text: 'Layout',
                    value: 'layout'
                }
            ],
            items: [
                {
                    id: 1,
                    noseri: 'TD123',
                    layout: {
                        id: 7,
                        label: 'Blok B'
                    }
                },
                {
                    id: 2,
                    noseri: 'TD456',
                    layout: {
                        id: 7,
                        label: 'Blok B'
                    }
                }
            ],
            layout: [
                {
                    id: 7,
                    label: 'Blok B'
                },
                {
                    id: 5,
                    label: 'Blok D'
                },
                {
                    id: 6,
                    label: 'E10'
                },
                {
                    id: 4,
                    label: 'E14'
                },
                {
                    id: 1,
                    label: 'E7'
                }
            ],
            noSeriSelected: [],
            checkAll: false,
            showModal: false
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        checkedAll() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.items
            } else {
                this.noSeriSelected = []
            }
        },
        checkOne(item) {
            if (this.noSeriSelected.find(id => id.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(id => id.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split('\n')
            noseriarray = noseriarray.filter((data) => data !== '')
            noseriarray = [...new Set(noseriarray)]
            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.items.length; j++) {
                    if (noseriarray[i] === this.items[j].noseri) {
                        this.checkOne(this.items[j])
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Peringatan', 'Nomor Seri ' + noserinotfound.join(', ') + ' tidak ditemukan', 'warning')
            }
        },
        changeLayout(layout) {
            // change all layout on noseriselected
            this.noSeriSelected.forEach(item => {
                item.layout = layout
            })

        },
        openChangeLayout() {
            if (this.noSeriSelected.length === 0) {
                this.$swal('Peringatan', 'Pilih nomor seri terlebih dahulu', 'warning')
                return
            }
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalLayout').modal('show')
            })
        },
        closeChangeLayout() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        openSeriviatext() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalChecked').modal('show')
            })
        },
        closeSeriviatext() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        simpan() {
            if (this.noSeriSelected.length === 0) {
                this.$swal('Peringatan', 'Pilih nomor seri terlebih dahulu', 'warning')
                return
            }
            this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
            this.closeModal()
            this.$emit('refresh')
        }
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.items.length) {
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
        <seriviatext v-if="showModal" @close="closeSeriviatext" @submit="submit" />
        <modalLayout v-if="showModal" @close="closeChangeLayout" @submit="changeLayout" />
        <div class="modal fade modalDetail" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Penerimaan Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-3">
                                    <div class="col"><label for="">Nomor Referensi</label>
                                        <div class="card nomor-so">
                                            <div class="card-body"><span id="total_tf">{{ detail.no_ref }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Tanggal Masuk</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body"><span id="belum_tf">{{ detail.tgl_masuk }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Produk</label>
                                        <div class="card nomor-po">
                                            <div class="card-body"><span id="sudah_tf">{{ detail.nama }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="detail.status == 'batal'">
                                    <label for="">Alasan Batal</label>
                                    <div class="card card-detail">
                                        <div class="card-body"><span id="sudah_tf">Lorem ipsum dolor sit amet
                                                consectetur
                                                adipisicing elit. Minima facilis quis cupiditate aut praesentium
                                                perferendis, architecto natus tempora, omnis corporis ad atque, delectus
                                                tenetur consequuntur magnam commodi animi sunt impedit.</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <button class="btn btn-primary" @click="openSeriviatext">Pilih Nomor Seri Via
                                            Text</button>
                                        <button class="btn btn-info" @click="openChangeLayout">Ubah Layout</button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="search"
                                            placeholder="Cari..." />
                                    </div>
                                </div>
                                <data-table :headers="headers" :items="items" :search="search">
                                    <template #header.id>
                                        <input type="checkbox" :checked="checkAll" @click="checkedAll" />
                                    </template>
                                    <template #item.id="{ item }">
                                        <input type="checkbox"
                                            :checked="noSeriSelected && noSeriSelected.find(id => id.id === item.id)"
                                            @click="checkOne(item)">
                                    </template>
                                    <template #item.layout="{ item }">
                                        <v-select :options="layout" v-model="item.layout"></v-select>
                                    </template>
                                </data-table>
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
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>