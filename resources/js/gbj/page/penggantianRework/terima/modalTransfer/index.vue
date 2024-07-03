<script>
import seriviatext from '../../../penerimaanRework/transfer/modalTransfer/seriviatext.vue';
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        seriviatext,
        DataTable,
    },
    props: ['dataSelected'],
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Varian',
                    value: 'varian'
                },
                {
                    text: 'Layout',
                    value: 'layout'
                },
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
                    id: 1,
                    label: 'E7'
                },
            ],
            noSeriSelected: [],
            checkedAll: false,
            showmodalviatext: false,
            search: '',
            isScan: false,
        }
    },
    methods: {
        // close modal
        closeModal() {
            $('.modalDetailTransfer').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        // date time format
        checkAll() {
            this.checkedAll = !this.checkedAll;
            if (this.checkedAll) {
                this.noSeriSelected = this.dataSelected.item.map(item => item);
            } else {
                this.noSeriSelected = [];
            }
        },
        // select noseri
        selectNoSeri(item) {
            if (this.noSeriSelected.find(noseri => noseri === item)) {
                this.noSeriSelected = this.noSeriSelected.filter(noseri => noseri !== item);
                this.checkedAll = false;
            } else {
                this.noSeriSelected.push(item);
            }

            if (this.noSeriSelected.length === this.dataSelected.item.length) {
                this.checkedAll = true;
            } else {
                this.checkedAll = false;
            }
        },
        // show modal via text
        showSeriText() {
            this.showmodalviatext = true
            $('.modalDetailTransfer').modal('hide')
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
            })
        },
        // close modal via text
        closeModalSeriviatext() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalDetailTransfer').modal('show')
            })
        },
        // submit
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            // remove empty string
            noseriarray = noseriarray.filter((data) => data !== "")

            // remove duplicate
            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.dataSelected.item.length; j++) {
                    if (noseriarray[i] === this.dataSelected.item[j].noseri) {
                        if (!this.noSeriSelected.find((data) => data.noseri === noseriarray[i])) {
                            this.noSeriSelected.push(this.dataSelected.item[j])
                        }
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (this.noSeriSelected.length == this.dataSelected.item.length) {
                this.checkedAll = true
            } else {
                this.checkedAll = false
            }

            noserinotfound = [...new Set(noserinotfound)]

            if (noserinotfound.length > 0 && noserinotfound != "") {
                this.$swal('Peringatan', `Nomor Seri ${noserinotfound.join(', ')} tidx1ak ditemukan`, 'warning')
            }
        },
        // auto select
        autoSelect() {
            if (this.isScan) {
                let noserinotfound = []

                let noseriarray = this.search.split(/[\n, \t]/)
                for (let i = 0; i < noseriarray.length; i++) {
                    let found = false
                    for (let j = 0; j < this.dataSelected.item.length; j++) {
                        if (noseriarray[i] === this.dataSelected.item[j].noseri) {
                            if (!this.noSeriSelected.find((data) => data.noseri === noseriarray[i])) {
                                this.noSeriSelected.push(this.dataSelected.item[j])
                            }
                            found = true
                            break
                        }
                    }

                    if (!found) {
                        noserinotfound.push(noseriarray[i])
                    }
                }

                this.search = ""

                if (this.noSeriSelected.length == this.dataSelected.item.length) {
                    this.checkedAll = true
                } else {
                    this.checkedAll = false
                }

                if (noserinotfound.length > 0 && noserinotfound != "") {
                    this.$swal('Peringatan', `Nomor Seri ${noserinotfound.join(', ')} tidak ditemukan`, 'warning')
                }
            }
        },
        // scan noseri
        scanSeri() {
            this.isScan = !this.isScan
            this.search = ""
            this.$nextTick(() => {
                this.$refs.search.focus()
            })
        },
        // change layout
        changeLayout(event) {
            console.log(event);
            // change all layout noseriSelected
            this.noSeriSelected.forEach((data) => {
                data.layout = event
            })
            this.search = "t"
            this.search = ""
        },
        // transfer
        transfer() {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah ditransfer tidak dapat dikembalikan lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Transfer!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil ditransfer',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        this.closeModal()
                    })
                }
            
            })
        }
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalSeriviatext" @submit="submit" />

        <div class="modal fade modalDetailTransfer" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Penerimaan Penggantian Rework</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    <div class="col-6">
                                        <label for="">No Urut</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{
                                                    dataSelected.no_urut
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <label for="">Tanggal Penerimaan</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{
                                                    dateTimeFormat(dataSelected.tgl_tf)
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <label for="">Nama Produk</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ dataSelected.nama }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <label for="">Jumlah</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">{{ dataSelected.jumlah }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="">Keterangan</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <p class="card-text">
                                                    {{ dataSelected.keterangan }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                        <input type="text" class="form-control mb-1" v-model="search"
                                            placeholder="Cari No Seri" ref="search" @keyup.enter="autoSelect" />
                                        <v-select :options="layout" v-if="noSeriSelected.length > 0"
                                            @input="changeLayout($event)" placeholder="Ubah Layout Dipilih"></v-select>
                                    </div>
                                </div>
                                <DataTable :headers="headers" :items="dataSelected.item" :search="search">
                                    <template #header.id>
                                        <div>
                                            <input type="checkbox" :checked="checkedAll" @click="checkAll">
                                        </div>
                                    </template>

                                    <template #item.id="{ item }">
                                        <div>
                                            <input type="checkbox"
                                                :checked="noSeriSelected && noSeriSelected.find(noseri => noseri === item)"
                                                @click="selectNoSeri(item)">
                                        </div>
                                    </template>
                                    <template #item.layout="{ item }">
                                        <div>
                                            <v-select :options="layout" v-model="item.layout"
                                                placeholder="Pilih Layout"></v-select>
                                        </div>
                                    </template>
                                </DataTable>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-success" @click="transfer">Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717fe1;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-akn {
    background-color: #df7458;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-po {
    background-color: #85d296;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.instansi {
    background-color: #36425e;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>