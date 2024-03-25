<script>
import Header from '../../components/header.vue'
import moment from 'moment'
import detail from './detail.vue'
export default {
    components: {
        Header,
        detail
    },
    data() {
        return {
            title: 'Penerimaan Finish Goods',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Penerimaan Finish Goods',
                    link: '/penerimaan-finish-goods'
                }
            ],
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_ref'
                },
                {
                    text: 'Tanggal Masuk',
                    value: 'tgl_masuk'
                },
                {
                    text: 'Bagian',
                    value: 'bagian'
                },
                {
                    text: 'Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [
                {
                    no: 1,
                    no_ref: 'NOMOR BPPB',
                    tgl_masuk: '23 September 2024',
                    nama: 'Produk 1',
                    bagian: 'Produksi',
                    jumlah: 100,
                    status: 'perakitan'
                },
                {
                    no: 2,
                    no_ref: 'NOMOR SO',
                    tgl_masuk: '25 Januari 2024',
                    nama: 'Produk 1',
                    bagian: 'Penjualan',
                    jumlah: 100,
                    status: 'retur'
                },
                {
                    no: 3,
                    no_ref: 'NOMOR SO',
                    tgl_masuk: '23 Februari 2024',
                    nama: 'Produk 1',
                    bagian: 'Logistik',
                    jumlah: 100,
                    status: 'batal'
                }
            ],
            search: '',
            years: new Date().getFullYear(),
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        showDetailModal(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        statusBadge(status) {
            switch (status) {
                case 'perakitan':
                    return {
                        text: 'Perakitan',
                        color: 'badge-primary'
                    }

                case 'retur':
                    return {
                        text: 'Retur',
                        color: 'badge-warning'
                    }

                case 'batal':
                    return {
                        // batal mengikuti data noseri dari departemen mana saja
                        text: 'Batal',
                        color: 'badge-danger'
                    }

                default:
                    return {
                        text: status,
                        color: 'badge-secondary'
                    }
            }
        }
    },
    computed: {
        yearsComputed() {
            let years = []
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'))
            }
            return years
        },

    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <detail v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-1 col-form-label">Tahun</label>
                            <div class="col-2">
                                <v-select :options="yearsComputed" v-model="years"></v-select>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                    </div>
                </div>
                <data-table :headers="headers" :items="items" :search="search">
                    <template #item.status="{ item }">
                        <span :class="'badge ' + statusBadge(item.status).color">{{ statusBadge(item.status).text
                            }}</span>
                    </template>
                    <template #item.aksi="{ item }">
                        <button class="btn btn-outline-primary btn-sm" @click="showDetailModal(item)">
                            <i class="far fa-edit"></i>
                            Terima
                        </button>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>