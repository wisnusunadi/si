<script>
import detail from './modalDetail.vue'
export default {
    components: {
        detail
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
                    value: 'po'
                },
                {
                    text: 'Customer',
                    value: 'customer'
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
            items: [
                {
                    no: 1,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    akn: 'AKN-2021-0001',
                    customer: 'PT. ABC',
                    batas_transfer: '18 Februari 2022',
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
    }
}
</script>
<template>
    <div class="card">
        <detail v-if="showModal" :detailSelected="detailSelected" @closeModal="showModal = false" />
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
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-print"></i>
                            SPPB
                        </button>
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>