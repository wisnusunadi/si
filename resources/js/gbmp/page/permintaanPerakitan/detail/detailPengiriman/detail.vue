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
            ]
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
            <data-table :headers="header" :items="produk.detail" />
        </div>
    </div>
</template>