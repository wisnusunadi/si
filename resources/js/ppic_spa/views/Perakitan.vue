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
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in tablePerakitan" :key="index">
                                <td>{{ item.periode}}</td>
                                <td>{{ item.no_bppb}}</td>
                                <td v-html="item.nama"></td>
                                <td>{{item.jumlah}}</td>
                                <td>{{item.tanggal_mulai}}</td>
                                <td v-html="item.tanggal_selesai"></td>
                                <td v-html="item.progres"></td>
                                <td>{{item.status}}</td>
                                <td><button class="button is-primary" @click="modal(item.aksi, item.nama)" v-if="item.keterangan || item.keterangan_transfer">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal" :class="{'is-active': showModal}">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title" v-html="this.nama_produk"></p>
                    <button class="delete" aria-label="close" @click="showModal = false"></button>
                </header>
                <section class="modal-card-body">
                    <div v-if="this.loading">Loading...</div>
                    <div class="field" v-else>
                    <label class="label">Keterangan</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="" v-model="detail" readonly>
                    </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    import axios from 'axios';

    /**
     * @vue-event {Array} loadData - this function initialized datatables with server-side option
     */

    export default {
        name: "Perakitan",
        data() {
            return {
                tablePerakitan: null,
                detail: null,
                error: null,
                showModal: false,
                loading: false,
                nama_produk: null
            }
        },

        methods: {
            async loadData() {
                try {
                    this.$store.commit("setIsLoading", true);
                    await axios.get("/api/ppic/datatables/perakitan", {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }).then((response) => {
                        this.tablePerakitan = response.data.data;
                    });
                    $('#table').DataTable({
                        "paging": true,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json"
                        }
                    });
                    this.$store.commit("setIsLoading", false);
                } catch (err) {
                    this.error = err;
                }
            },
            async modal(data, nama) {
                this.showModal = true;
                try {
                    this.loading = true;
                    this.nama_produk = nama;
                    await axios.get("/api/ppic/datatables/perakitandetail/"+data, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }).then((response) => {
                        this.detail = response.data;
                    });
                    this.loading = false;
                } catch (err) {
                    this.error = err;
                }
            },
            checkToken(){
                if(localStorage.getItem('lokal_token') == null){
                    // event.preventDefault();
                    this.$swal({
                        title: 'Session Expired',
                        text: 'Silahkan login kembali',
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.value) {
                            this.logout()
                        }
                    })
                }
            },

            async logout() {
            await axios.post("/logout");
            document.location.href = "/";
            },
        },

        created() {
            this.checkToken();
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
