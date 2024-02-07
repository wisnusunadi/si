<script>
import axios from 'axios'
import Header from '../../components/header.vue'
import modalTransfer from './modalTransfer'
import modalUnggah from './modalUnggah.vue'
export default {
    components: {
        Header,
        modalTransfer,
        modalUnggah
    },
    data() {
        return {
            title: 'Transfer Produk Berdasarkan SO',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Transfer Produk Berdasarkan SO',
                    link: '/gbj/bso'
                }
            ],
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor SO',
                    value: 'so'
                },
                {
                    text: 'No PO',
                    value: 'no_po'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Batas Transfer',
                    value: 'tgl_kontrak'
                },
                {
                    text: 'Progress',
                    value: 'progress',
                    align: 'text-left'
                },
                {
                    text: 'Status',
                    value: 'status',
                    align: 'text-left'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    align: 'text-left'
                }
            ],
            items: [],
            search: '',
            detailSelected: {},
            showModalTransfer: false,
            showModalUnggah: false
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.commit('setLoading', true)
                const { data } = await axios.get('/api/tfp/data-so', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                this.items = data.map((item, index) => {
                    return {
                        ...item,
                        no: index + 1,
                        tgl_kontrak: this.dateFormat(item.tgl_kontrak),
                        persentase_qc: this.persentase(item.jumlah_qc, item.jumlah),
                        persentase_gudang: this.persentase(item.jumlah_gdg, item.jumlah)
                    }
                })
            } catch (error) {
                console.error(error)
            } finally {
                this.$store.commit('setLoading', false)
            }
        },
        persentase(jmlPerItem, jmlTotal) {
            let item = parseInt(jmlPerItem)
            let total = parseInt(jmlTotal)
            return Math.round((item / total) * 100)
        },
        openModalTransfer(detail) {
            this.detailSelected = detail
            this.showModalTransfer = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        download(id) {
            window.open(`/api/v2/gbj/template_so/${id}`, '_blank')
        },
        openTemplate(item) {
            this.showModalUnggah = true
            this.detailSelected = item
            this.$nextTick(() => {
                $('.modalUnggah').modal('show')
            })
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <modalTransfer v-if="showModalTransfer" @closeModal="showModalTransfer = false" :data="detailSelected"
            @refresh="getData" />
        <modalUnggah v-if="showModalUnggah" @closeModal="showModalUnggah = false" :data="detailSelected"
            @refresh="getData" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight"><input type="text" v-model="search" class="form-control"
                            placeholder="Cari..."></div>
                </div>
                <data-table :headers="headers" :items="items" :search="search" v-if="!$store.state.loading">
                    <template #item.progress="{ item }">
                        <div>
                            <span class="badge badge-info">QC: {{ item.jumlah_qc }} ({{ item.persentase_qc }}%)</span> <br>
                            <span class="badge badge-warning">Gudang: {{ item.jumlah_gdg }} ({{ item.persentase_gudang
                            }}%)</span>
                        </div>
                    </template>
                    <template #item.status="{ item }">
                        <div>
                            <span class="badge badge-success" v-if="item.jumlah_siap > 0">Produk Sudah disiapkan</span>
                            <span class="badge badge-danger" v-else>Produk belum disiapkan</span>
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div v-if="item.jumlah_siap > 0">
                            <button class="btn btn-outline-primary btn-sm" @click="openModalTransfer(item)">
                                <i class="fas fa-plus"></i>
                                Siapkan Produk
                            </button>
                            <button class="btn btn-outline-dark btn-sm" @click="download(item.id)">
                                <i class="fas fa-download"></i>
                                Template
                            </button>
                            <button class="btn btn-outline-info btn-sm" @click="openTemplate(item)">
                                <i class="fas fa-file-import"></i>
                                Unggah
                            </button>
                        </div>
                        <div v-else>Siapkan Produk Dahulu</div>
                    </template>
                </data-table>
                <div v-else class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>