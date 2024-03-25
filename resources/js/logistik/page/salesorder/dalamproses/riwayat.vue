<script>
export default {
    props: ['items'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no',
                    align: 'text-left',
                },
                {
                    text: 'No SJ',
                    value: 'sj',
                    align: 'text-left',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    align: 'text-left',
                }
            ],
            search: '',
        }
    },
    methods: {
        cetakSJ(id) {
            window.open(`/logistik/pengiriman/prints/${id}`, '_blank');
        },
        showModalEdit(item) {
            this.$emit('edit', item)
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-warning btn-sm" @click="showModalEdit(item)">
                    <i class="fa fa-pen"></i>
                </button>
                <button class="btn btn-primary btn-sm" @click="cetakSJ(item.id)">
                    <i class="fas fa-print"></i>
                </button>
            </template>
        </data-table>
    </div>
</template>