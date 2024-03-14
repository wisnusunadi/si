<script>
export default {
    props: ['produk'],
    data() {
        return {
            header: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Kedatangan',
                    value: 'kedatangan',
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tanggal_transfer',
                },
                {
                    text: 'Operator',
                    value: 'operator',
                }
            ],
            search: '',
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-header">
            <p>{{ produk.transfer }}</p>
        </div>
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="header" :items="produk.detail" :search="search" />
        </div>
    </div>
</template>