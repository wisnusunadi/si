<template>
    <div>
        <h1 class="title">Data Perakitan</h1>
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="table__wrapper">
                    <table class="table is-fullwidth has-text-centered" id="table">
                        <thead>
                            <tr>
                                <th rowspan="2">Periode</th>
                                <th rowspan="2">No BPPB</th>
                                <th rowspan="2">Nama Produk</th>
                                <th rowspan="2">Jumlah</th>
                                <th colspan="2">Tanggal</th>
                                <th rowspan="2">Progres</th>
                                <th rowspan="2">Status</th>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    import mixins from "../mixins";

    /**
     * @vue-event {Array} loadData - this function initialized datatables with server-side option
     */

    export default {
        name: "Perakitan",

        methods: {
            loadData() {
                $("#table").DataTable({
                    ajax: "/api/ppic/datatables/perakitan",
                    columns: [{
                            data: "periode",
                        },
                        {
                            data: 'no_bppb',
                        },
                        {
                            data: "nama",
                        },
                        {
                            data: "jumlah",
                        },
                        {
                            data: "tanggal_mulai",
                        },
                        {
                            data: "tanggal_selesai",
                        },
                        {
                            data: "progres",
                        },
                        {
                            data: function (row) {
                                return mixins.change_status(row["status"]);
                            },
                        },
                    ],
                });
            },
        },

        mounted() {
            this.loadData();
        },

    };

</script>
<style>
    .table__wrapper {
        overflow-x: auto;
    }

</style>
