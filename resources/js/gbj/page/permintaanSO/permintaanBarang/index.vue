<script>
import pagination from '../../../components/pagination.vue';
import detail from './detail.vue';
export default {
    components: {
        pagination,
        detail
    },
    props: ['items'],
    data() {
        return {
            search: '',
            showModal: false,
            detailSelected: {},
            header: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'produkk'
                },
                {
                    text: 'Permintaan',
                    children: [
                        {
                            text: 'Total',
                            value: 'permintaan'
                        },
                        {
                            text: 'Sisa',
                            value: 'sisa'
                        }
                    ]
                },
                {
                    text: 'Jumlah Transfer',
                    value: 'count_transfer'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ]
        }
    },
    methods: {
        openModalDetail(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            });
        },
    },
}
</script>
<template>
    <div class="card">
        <detail :detailSelected="detailSelected" v-if="showModal" @closeModal="showModal = false" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :search="search" :headers="header" :items="items">
                <template #item.aksi="{item}">
                    <button class="btn btn-outline-info btn-sm" @click="openModalDetail(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </template>
            </data-table>
        </div>
    </div>
</template>