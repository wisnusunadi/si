<script>
import produkComponents from './produk.vue'
export default {
    props: ['items'],
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
                    value: 'no_po'
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
            if (item.jumlah_tf == 0) {
                return {
                    text: 'Belum Diinfokan',
                    color: 'badge-danger'
                }
            } else if (item.jumlah == item.jumlah_tf) {
                return {
                    text: 'Sudah Diinfokan',
                    color: 'badge-success'
                }
            } else {
                return {
                    text: 'Sudah Diinfokan Sebagian',
                    color: 'badge-warning'
                }
            }
        },
    },
}
</script>
<template>
    <div class="card">
        <produkComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" @refresh="$emit('refresh')" />
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