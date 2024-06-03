<script>
import Header from '../../components/header.vue'
import moment from 'moment'
import detail from './detail.vue'
import axios from 'axios'
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
                    value: 'datetime'
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
                    datetime: '23 September 2024 12:00',
                    nama: 'Produk 1',
                    bagian: 'Produksi',
                    jumlah: 100,
                    status: 'produksi'
                },
                {
                    no: 2,
                    no_ref: 'NOMOR SO',
                    datetime: '25 Januari 2024 12:00',
                    nama: 'Produk 1',
                    bagian: 'Penjualan',
                    jumlah: 100,
                    status: 'retur'
                },
                {
                    no: 3,
                    no_ref: 'NOMOR SO',
                    datetime: '23 Februari 2024 12:00',
                    nama: 'Produk 1',
                    bagian: 'Logistik',
                    jumlah: 100,
                    status: 'batal'
                },
                {
                    no: 4,
                    no_ref: 'NOMOR PERMINTAAN',
                    datetime: '23 Maret 2024 12:00',
                    nama: 'Produk 1',
                    bagian: 'Produksi',
                    jumlah: 10,
                    status: 'peminjaman',
                }
            ],
            search: '',
            years: new Date().getFullYear(),
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get(`/api/tfp/rakit?tahun=${this.years}`)
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        datetime: this.dateTimeFormat(item.timestamp),
                        ...item
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        showDetailModal(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        statusBadge(status) {
            switch (status) {
                case 'produksi':
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

                case 'peminjaman':
                    return {
                        text: 'Peminjaman',
                        color: 'badge-info'
                    }

                default:
                    return {
                        text: status,
                        color: 'badge-secondary'
                    }
            }
        }
    },
    created() {
        this.getData()
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
        <detail v-if="showModal" @close="showModal = false" :detail="detailSelected" @refresh="getData" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-1 col-form-label">Tahun</label>
                            <div class="col-2">
                                <v-select :options="yearsComputed" v-model="years" @input="getData"></v-select>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                    </div>
                </div>
                <data-table :headers="headers" :items="items" :search="search" v-if="!$store.state.loading">
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
                <div v-else class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
