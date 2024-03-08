<script>
import axios from 'axios'
import modalDetail from '../dalamProses/modalDetail.vue'
export default {
    components: {
        modalDetail
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
            items: [],
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
        async getData() {
            try {
                const { data } = await axios.get(`/api/tfp/sudah-dicek`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                        batas_transfer: this.dateFormat(item.batas)
                    }
                })
            } catch (error) {
                console.error(error)
            }
        },
        cetakSPPB(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank')
        },
    },
    created() {
        this.getData()
    }
}
</script>

<template>
    <div class="card">
        <modalDetail :detailSelected="detailSelected" v-if="showModal" @closeModal="showModal = false" />
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