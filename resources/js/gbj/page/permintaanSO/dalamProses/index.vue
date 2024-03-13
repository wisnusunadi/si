<script>
import axios from 'axios'
import detail from './modalDetail.vue'
export default {
    components: {
        detail
    },
    props: ["items"],
    data() {
        return {
            search: '',
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
                    text: 'Nomor PO',
                    value: 'no_po'
                },
                {
                    text: 'Customer',
                    value: 'divisi'
                },
                {
                    text: 'Batas Transfer',
                    value: 'batas_transfer'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        showDetail(item) {
            this.detailSelected = {
                detailOpen: true,
                ...item,
            }
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        showSiapkanProduk(item) {
            this.detailSelected = {
                detailOpen: false,
                ...item,
            }
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
        cetakSPPB(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank')
        },
        refresh() {
            this.$emit('refresh')
        }
    },
}
</script>
<template>
    <div class="card">
        <detail v-if="showModal" :detailSelected="detailSelected" @closeModal="showModal = false" @refresh="refresh" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="headers" :items="items" :search="search">
                <template #item.aksi="{ item }">
                    <div>
                        <button class="btn btn-outline-success btn-sm" @click="showDetail(item)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                        <button class="btn btn-sm btn-outline-primary" @click="showSiapkanProduk(item)">
                            <i class="fas fa-plus"></i>
                            Siapkan Produk
                        </button>
                        <button class="btn btn-sm btn-outline-primary" v-if="item.no_po != null && item.tgl_po != null"
                            @click="cetakSPPB(item.id)">
                            <i class="fas fa-print"></i>
                            SPPB
                        </button>
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>