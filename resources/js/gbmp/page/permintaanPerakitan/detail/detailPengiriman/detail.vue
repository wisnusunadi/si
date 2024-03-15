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
            ],
            search: '',
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-5">
                    <p>{{ produk.transfer }}</p>
                </div>
                <div class="col-5">
                    <div class="p-2">
                        <div class="margin">
                            <small class="text-muted">Tanggal Transfer</small>
                        </div>
                        <div class="margin">
                            <b>{{ produk.tgl_tf }}</b>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="p-2">
                        <div class="margin">
                            <small class="text-muted">Operator</small>
                        </div>
                        <div class="margin">
                            <b>{{ produk.operator }}</b>
                        </div>
                    </div>
                </div>
            </div>
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