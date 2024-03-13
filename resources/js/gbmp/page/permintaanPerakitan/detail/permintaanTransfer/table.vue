<script>
export default {
    props: ['transfer'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Kedatangan',
                    value: 'kedatangan'
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tgltransfer'
                },
                {
                    text: 'Pentransfer',
                    value: 'pentransfer'
                }
            ],
            items:[]
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">Transfer {{ transfer }}</div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search" />
    </div>
</template>