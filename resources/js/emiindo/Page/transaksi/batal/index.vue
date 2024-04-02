<script>
import axios from 'axios';
import alasanComponents from './alasanComponents.vue';
export default {
    props: ['batal'],
    components: {
        alasanComponents
    },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Qty',
                    value: 'qty'
                },
                {
                    text: 'Jumlah Batal',
                    value: 'jumlah'
                }
            ],
            items: [
                // {
                //     no: 1,
                //     nama: 'Produk 1',
                //     qty: 10,
                //     jumlah: 0
                // },
                // {
                //     no: 2,
                //     nama: 'Produk 2',
                //     qty: 20,
                //     jumlah: 0
                // },
                // {
                //     no: 3,
                //     nama: 'Produk 3',
                //     qty: 30,
                //     jumlah: 0
                // }
            ],
            showModalAlasan: false
        }
    },
    methods: {
        closeModal() {
            $('.modalBatal').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            })
        },
        simpan() {
            let dataJumlahNotZero = this.items.filter(item => item.jumlah > 0)
            if (dataJumlahNotZero.length > 0) {
                this.showModalAlasan = true
                this.$nextTick(() => {
                    $('.modalBatal').modal('hide')
                    $('.modalAlasanBatal').modal('show')
                })
            } else {
                this.$swal('Peringatan', 'Jumlah batal tidak boleh kosong', 'warning')
            }
        },
        closeModalAlasan() {
            this.showModalAlasan = false
            this.$nextTick(() => {
                $('.modalBatal').modal('show')
            })
        },
        closeAllModal() {
            this.closeModal()
            this.$emit('refresh')
        },
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/penjualan/batal_po/detail_paket/${this.batal.pesanan_id}`)
                this.items = data.map((item, index) => {
                    return {
                        ...item,
                        no: index + 1,
                        jumlah: 0
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    watch: {
        // jika jumlah batal di isi melebihi qty maka akan di reset menjadi qty
        items: {
            handler() {
                this.items.forEach(item => {
                    if (item.jumlah > item.qty) {
                        item.jumlah = item.qty
                    } else if (item.jumlah < 0) {
                        item.jumlah = 0
                    }
                })
            },
            deep: true
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <alasanComponents :items="items" :batal="batal" v-if="showModalAlasan" @close="closeModalAlasan" @closeAllModal="closeAllModal" @refresh="$emit('refresh')" />
        <div class="modal fade modalBatal" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Batal PO</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card removeshadow">
                            <div class="card-body border-0">
                                <h5 class="card-title pl-2 py-2">
                                    <b>{{ batal?.nama_customer }}</b>
                                </h5>
                                <ul class="fa-ul card-text">
                                    <li class="py-2">
                                        <span class="fa-li">
                                            <i class="fas fa-address-card fa-fw"></i>
                                        </span>
                                        {{ batal?.alamat }}
                                    </li>
                                    <li class="py-2">
                                        <span class="fa-li">
                                            <div class="fas fa-map-marker-alt fa-fw"></div>
                                        </span>
                                        <em class="text-muted" v-if="!batal?.provinsi">Belum Tersedia</em>
                                        {{ batal?.provinsi?.nama }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card card-outline card-tabs">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#" class="nav-link active" id="informasi-tab" data-toggle="tab"
                                            data-target="#informasi" role="tab" aria-controls="informasi"
                                            aria-selected="true">Informasi</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#" class="nav-link" id="produk-tab" data-toggle="tab"
                                            data-target="#produk" role="tab" aria-controls="produk"
                                            aria-selected="false">Produk</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="informasi" role="tabpanel"
                                        aria-labelledby="informasi-tab">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div>
                                                        <small class="text-muted">No SO</small>
                                                    </div>
                                                    <div>
                                                        <b> {{ batal?.so }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <persentase :persentase="batal.persentase"
                                                        v-if="!cekIsString(batal.persentase)" />
                                                    <span class="red-text badge" v-else>{{ batal.persentase }}</span>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b>
                                                            {{ batal?.no_po }}
                                                        </b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b>
                                                            {{ dateFormat(batal?.pesanan?.tgl_po) }}
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No DO</small></div>
                                                    <div><b v-if="batal?.no_do">
                                                            {{ batal?.no_do }}
                                                        </b>
                                                        <em v-else>Belum ada</em>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal DO</small></div>
                                                    <div><b v-if="batal?.pesanan?.tgl_do">
                                                            {{ dateFormat(batal?.pesanan?.tgl_do) }}
                                                        </b>
                                                        <em v-else>Belum ada</em>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-row-reverse bd-highlight">
                                                            <div class="p-2 bd-highlight">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Cari..." v-model="search">
                                                            </div>
                                                        </div>
                                                        <data-table :headers="headers" :items="items" :search="search">
                                                            <template #item.jumlah="{ item }">
                                                                <input type="text" class="form-control"
                                                                    v-model="item.jumlah" @keypress="numberOnly">
                                                            </template>
                                                        </data-table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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