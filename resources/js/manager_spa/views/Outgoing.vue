<template>
    <div class="columns">
        <div class="column is-full">
            <h1 class="title">QC Outgoing</h1>
            <div class="tabs is-toggle">
                <ul>
                    <li class="is-active">
                        <a>
                            <span class="icon is-small"
                                ><i class="fa fa-spinner" aria-hidden="true"></i
                            ></span>
                            <span>Dalam Pengujian</span>
                        </a>
                    </li>
                    <li>
                        <a>
                            <span class="icon is-small"
                                ><i class="fas fa-check" aria-hidden="true"></i
                            ></span>
                            <span>Selesai Pengujian</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="columns">
                <div class="column is-full">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table is-hoverable is-striped is-fullwidth"
                                    id="dalamujitable"
                                >
                                    <thead>
                                        <tr>
                                            <thead>
                                                <th>No SO</th>
                                                <th>No PO</th>
                                                <th>Batas Pengujian</th>
                                                <th>Customer</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </thead>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";
export default {
    name: "Outgoing",
    data() {
        return {
            data_belum_uji: [],

            batalModal: false,
            detailSo: false,
            tabs: false,
        };
    },

    methods: {
        async loadData() {
            await axios
                .post("/api/qc/so/data/semua")
                .then((response) => {
                    this.data_belum_uji = response.data.data;
                })
                .then(() =>
                    $("#dalamujitable").DataTable({
                        pagingType: "simple_numbers_no_ellipses",
                    })
                );
        },
    },

    mounted() {
        this.loadData();
    },
};
</script>
