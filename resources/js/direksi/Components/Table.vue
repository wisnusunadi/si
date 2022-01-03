<template>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-layer-group"></i> Perakitan Bulan
                {{ nextMonth() }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered" id="table_produk_perakitan">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="2" class="text-center">Tanggal</th>
                                <th rowspan="2">Produk</th>
                                <th rowspan="2">Jumlah Rakit</th>
                            </tr>
                            <tr>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
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
    require("datatables.net-bs4");
    export default {
        created() {
            $(document).ready(function () {
                $.noConflict();
                $('#table_produk_perakitan').DataTable({
                    processing: true,
                    ajax: {
                        url: '/api/prd/plan',
                        type: 'POST',
                    },
                    "autoWidth": false,
                    columns: [{
                            data: "start"
                        },
                        {
                            data: "end"
                        },
                        {
                            data: "produk"
                        },
                        {
                            data: "jml"
                        },
                    ],
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                    }
                })
            });
        },

        methods: {
            nextMonth() {
                const monthNames = ["Bulan Kosong", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

                const current = new Date();
                const date = `${monthNames[current.getMonth() + 1, 1]}`;
                return date;
            }
        }
    }

</script>
