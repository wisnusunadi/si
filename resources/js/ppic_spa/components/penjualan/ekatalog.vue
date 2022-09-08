<template >
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title">Penjualan</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Urut</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor AKN</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Buat</th>
                                    <th>Tanggal Edit</th>
                                    <th>Tanggal Delivery</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(penjualanekatalog, idx) in penjualanekatalogs" :key="idx">
                                    <td>{{ idx+1 }}</td>
                                    <td>{{ penjualanekatalog.no_urut }}</td>
                                    <td>{{ penjualanekatalog.so }}</td>
                                    <td v-html="checkdata(akn(penjualanekatalog.no_paket_ppic, penjualanekatalog.status_ppic))"></td>
                                    <td>{{ penjualanekatalog.nopo }}</td>
                                    <td>{{ penjualanekatalog.tgl_buat }}</td>
                                    <td>{{ checkdata(penjualanekatalog.tgl_edit) }}</td>
                                    <td v-html="checkdata(penjualanekatalog.tgl_kontrak)"></td>
                                    <td>{{ penjualanekatalog.nama_customer }}</td>
                                    <td v-html="status(penjualanekatalog.status_ppic)"></td>
                                    <td><button class="button is-info is-light is-small" @click="detailekatalog(penjualanekatalog.id)">Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" :class="{'is-active': modalEkportsEkatalog}">
                <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head">
                        <p class="modal-card-title">Export Laporan</p>
                        <button class="delete" @click="modalEkportsEkatalog = false"></button>
                        </header>
                        <section class="modal-card-body">
                            {{ detailpenjualanekatalog }}
                        </section>
                        <footer class="modal-card-foot">
                        <button class="button is-danger" @click="modalEkportsEkatalog = false">Cancel</button>
                        </footer>
                    </div>
            </div>
    </div>
</template>
<script>
    import axios from 'axios';
    import $ from "jquery";
    export default {
        data() {
            return {
                penjualanekatalogs: [],
                modalEkportsEkatalog: false,
                detailpenjualanekatalog: [],
            }
        },
        methods: {
            async getPenjualan() {
                try {
                    this.$store.commit('setIsLoading', true);
                    await axios.post('/penjualan/penjualan/ekatalog/data/semua')
                    .then(response => {
                        this.penjualanekatalogs = response.data.data;
                        this.$store.commit('setIsLoading', false);
                    })
                    .catch(error => {
                        console.log(error);
                    });
                } catch (error) {
                    console.log(error);
                }
            },
            akn(akn, status){
                switch (status) {
                    case 'batal':
                        return `${akn}<br> <span class="tag is-danger is-light">${status}</span>`
                    case 'negosiasi':
                        return `${akn}<br> <span class="tag is-warning is-light">${status}</span>`
                    case 'draft':
                        return `${akn}<br> <span class="tag is-info is-light">${status}</span>`
                    case 'sepakat':
                        return `${akn}<br> <span class="tag is-success is-light">${status}</span>`
                    default:
                        break;
                }
            },
            status(status){
                switch (status) {
                    case 'batal':
                        return `<span class="tag is-danger is-light">${status}</span>`
                    case 'penjualan':
                        return `<span class="tag is-danger is-light">${status}</span>`
                    default:
                        return `
                        <progress class="progress is-success" value="${status}" max="100">${status}%</progress>
                        <span><b>${status}%</b> Selesai</span>
                        `
                }
            },
            checkdata(data){
                if(data == null){
                    return '-'
                }else{
                    return data
                }
            },
            async detailekatalog(id){
                this.$store.commit('setIsLoading', true);
                await axios.get('/penjualan/penjualan/detail/ekatalog_ppic/'+id)
                .then(response => {
                    this.detailpenjualanekatalog = response.data;
                    this.$store.commit('setIsLoading', false);
                    this.modalEkportsEkatalog = true;
                })
            }
        },
        mounted() {
            this.getPenjualan();
        },
        updated() {
            $('.table').DataTable();
        }
    }
    </script>