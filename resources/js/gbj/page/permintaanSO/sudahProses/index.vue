<script>
import modalDetail from '../dalamProses/modalDetail.vue'
export default {
    components: {
        modalDetail
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
        cetakSPPB(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank')
        },
    },
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