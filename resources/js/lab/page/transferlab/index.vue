<script>
import pagination from "../../components/pagination.vue";
import Header from "../../components/header.vue";
import produk from "./produk.vue";
import loading from '../../components/loading.vue';
import axios from 'axios';
import riwayat from "./riwayatorder";
import riwayatseri from "./riwayatseri";
export default {
    components: {
        pagination,
        produk,
        Header,
        loading,
        riwayat,
        riwayatseri
    },
    data() {
        return {
            // header
            title: "Transfer Unit Kalibrasi",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Transfer Unit Kalibrasi",
                    link: "/lab/transfer",
                },
            ],
            search: "",
            searchEksternal: "",
            dataTable: [],
            dataTableEksternal: [],
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'pemilik_sertif'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false
                }
            ],
            modal: false,
            selectedSO: null,
            riwayatKalibrasi: [],
            showTabs: 'internal',
            transferKalibrasiNoSeri: [],
        };
    },
    methods: {
        transfer(data) {
            this.selectedSO = data;
            this.modal = true;
            this.$nextTick(() => {
                $(".modalProduk").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/tf").then((res) => res.data);
                const { data: riwayat_kalibrasi } = await axios.get(`/api/labs/tf_riwayat?years=${this.$store.state.years}`);
                const { data: noseri } = await axios.get(`/api/labs/tf_riwayat_seri?years=${this.$store.state.years}`);
                this.dataTable = data
                this.dataTableEksternal = data
                this.riwayatKalibrasi = riwayat_kalibrasi.map(item => {
                    return {
                        ...item,
                        tgl_transfer: this.formatDate(item.tgl_transfer),
                        detail: item.detail.map((produk, index) => {
                            return {
                                ...produk,
                                no: index + 1,
                                noseri: produk.noseri.map((noseri, index) => {
                                    return {
                                        ...noseri,
                                        no: index + 1,
                                    }
                                })
                            }
                        })
                    }
                })
                this.transferKalibrasiNoSeri = noseri.map(item => {
                    return {
                        ...item,
                        tanggal: this.formatDate(item.tgl_transfer),
                    }
                })
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        changeYear() {
            this.getData();
        },
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button"
                    @click="showTabs = 'internal'" role="tab" aria-controls="pills-home" aria-selected="true">Kalibrasi</a>

            </li>
            <!-- <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    @click="showTabs = 'eksternal'" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Eksternal</a>
            </li> -->
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    @click="showTabs = 'riwayat'" role="tab" aria-controls="pills-contact" aria-selected="false">Riwayat</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                v-if="showTabs == 'internal'">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-dalamproses-tab" data-toggle="pill"
                                    data-target="#pills-dalamproses" type="button" role="tab"
                                    aria-controls="pills-dalamproses" aria-selected="true">Dalam Proses</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                                    type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Selesai
                                    Proses</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-dalamproses" role="tabpanel"
                                aria-labelledby="pills-dalamproses-tab">
                                <produk v-if="modal" @close="modal = false" :headerSO="selectedSO" @refresh="getData" />
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..."
                                                v-model="search" />
                                        </div>
                                    </div>
                                </div>

                                <data-table :headers="headers" :items="dataTable" :search="search"
                                    v-if="!$store.state.loading">
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-outline-primary btn-sm" @click="transfer(item)">
                                            Transfer
                                        </button>
                                    </template>
                                </data-table>
                                <div class="spinner-border spinner-border-sm" role="status" v-else>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-riwayat" role="tabpanel"
                                aria-labelledby="pills-riwayat-tab">
                                <riwayat :dataRiwayat="riwayatKalibrasi" @changeYears="changeYear" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                v-if="showTabs == 'eksternal'">
                <div class="card">
                    <div class="card-body">
                        <produk v-if="modal" @close="modal = false" :headerSO="selectedSO" @refresh="getData" />
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..."
                                        v-model="searchEksternal" />
                                </div>
                            </div>
                        </div>

                        <data-table :headers="headers" :items="dataTableEksternal" :search="searchEksternal"
                            v-if="!$store.state.loading">
                            <template #item.aksi="{ item }">
                                <button class="btn btn-outline-primary btn-sm" @click="transfer(item)">
                                    Transfer
                                </button>
                            </template>
                        </data-table>
                        <div class="spinner-border spinner-border-sm" role="status" v-else>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                v-if="showTabs == 'riwayat'">
                <div class="card">
                    <div class="card-body">
                        <riwayatseri :noseri="transferKalibrasiNoSeri" @changeYears="getData" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
