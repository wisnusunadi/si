<script>
import produkComponents from './produk.vue'
export default {
    components: {
        produkComponents
    },
    data() {
        return {
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
                    text: 'Progress',
                    value: 'progress'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            items: [
                {
                    no: 1,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    sudah_transfer: 0,
                    total: 100,
                },
                {
                    no: 2,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    sudah_transfer: 20,
                    total: 100,
                },
                {
                    no: 2,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    sudah_transfer: 100,
                    total: 100,
                }
            ],
            detailSelected: {},
            showModal: false,
        }
    },
    methods: {
        showDetail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        progressTransfer(item) {
            if (item.sudah_transfer == item.total) {
                return {
                    text: 'Sudah Transfer',
                    color: 'badge-success'
                }
            } else if (item.sudah_transfer == 0) {
                return {
                    text: 'Belum Transfer',
                    color: 'badge-danger'
                }
            } else {
                return {
                    text: 'Sudah Transfer Sebagian',
                    color: 'badge-warning'
                }
            }
        }
    },
}
</script>
<template>
    <div class="card">
        <produkComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="headers" :items="items" :search="search">
                <template #item.progress="{ item }">
                    <div>
                        <span :class="'badge ' + progressTransfer(item).color">{{ progressTransfer(item).text }}</span>
                    </div>
                </template>
                <template #item.aksi="{ item }">
                    <button class="btn btn-sm btn-outline-info" @click="showDetail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </template>
            </data-table>
        </div>
    </div>
</template>