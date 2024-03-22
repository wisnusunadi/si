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
                $('.modalProduk').modal('show')
            })
        },
    },
}
</script>
<template>
    <div class="card">
        <produkComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search">
                </div>
            </div>
            <data-table :headers="headers" :items="items" :search="search">
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