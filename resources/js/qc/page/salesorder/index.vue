<script>
import Header from "../../components/header.vue";
import dalamproses from "./dalamproses";
import selesaiproses from "./selesaiproses";
import batalpo from "./batalpo";
import axios from "axios";
export default {
    components: {
        Header,
        dalamproses,
        selesaiproses,
        batalpo,
    },
    data() {
        return {
            title: "Sales Order",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "/",
                },
                {
                    name: "Sales Order Qc",
                    link: "/salesorder",
                },
            ],
            dalamProsesData: [],
            selesaiProsesData: [],
            batalPoData: [],
            jenisDalamProsesStatus: ["semua"],
            jenisSelesaiProsesStatus: ["semua"],
            isBatalPo: false,
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data: dalamproses } = await axios.post(
                    `/api/qc/so/data/${this.jenisDalamProsesStatus}`,
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "lokal_token"
                            )}`,
                        },
                    }
                );
                this.dalamProsesData = dalamproses.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    };
                });

                const { data: selesai } = await axios.post(
                    `/api/qc/so/data/selesai/${this.jenisSelesaiProsesStatus}`,
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "lokal_token"
                            )}`,
                        },
                    }
                );
                this.selesaiProsesData = selesai.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    };
                });

                const { data: batalPO } = await axios.get(
                    "/api/penjualan/batal_po/qc/show"
                );
                this.batalPoData = batalPO.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    };
                });

            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        filterDataStatusDalamProses(status) {
            if (this.jenisDalamProsesStatus.includes(status)) {
                this.jenisDalamProsesStatus =
                    this.jenisDalamProsesStatus.filter(
                        (item) => item !== status
                    );
            } else {
                this.jenisDalamProsesStatus =
                    this.jenisDalamProsesStatus.filter(
                        (item) => item !== "semua"
                    );
                this.jenisDalamProsesStatus.push(status);
            }

            if (this.jenisDalamProsesStatus.length === 0) {
                this.jenisDalamProsesStatus.push("semua");
            }

            this.$nextTick(() => {
                this.getData();
            });
        },
        filterDataStatusSelesaiProses(status) {
            if (this.jenisSelesaiProsesStatus.includes(status)) {
                this.jenisSelesaiProsesStatus =
                    this.jenisSelesaiProsesStatus.filter(
                        (item) => item !== status
                    );
            } else {
                this.jenisSelesaiProsesStatus =
                    this.jenisSelesaiProsesStatus.filter(
                        (item) => item !== "semua"
                    );
                this.jenisSelesaiProsesStatus.push(status);
            }

            if (this.jenisSelesaiProsesStatus.length === 0) {
                this.jenisSelesaiProsesStatus.push("semua");
            }

            this.$nextTick(() => {
                this.getData();
            });
        },
    },
    created() {
        this.getData();
    },
    watch: {
        'batalPoData': function (val) {
            if (val.length > 0) {
                this.isBatalPo = true;
            } else {
                this.isBatalPo = false;
            }
        },
    }
};
</script>
<template>
    <div class="font-medium">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body" v-if="!$store.state.loading">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link active"
                            id="pills-home-tab"
                            data-toggle="pill"
                            data-target="#pills-home"
                            type="button"
                            role="tab"
                            aria-controls="pills-home"
                            aria-selected="true"
                            >Dalam Proses</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="pills-profile-tab"
                            data-toggle="pill"
                            data-target="#pills-profile"
                            type="button"
                            role="tab"
                            aria-controls="pills-profile"
                            aria-selected="false"
                            >Selesai Proses</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="pills-contact-tab"
                            data-toggle="pill"
                            data-target="#pills-contact"
                            type="button"
                            role="tab"
                            aria-controls="pills-contact"
                            aria-selected="false"
                            >Batal PO
                            <span class="badge badge-danger" v-if="isBatalPo">
                                <span>
                                    Ada PO yang dibatalkan
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div
                        class="tab-pane fade show active"
                        id="pills-home"
                        role="tabpanel"
                        aria-labelledby="pills-home-tab"
                    >
                        <dalamproses
                            :dalam="dalamProsesData"
                            @filter="filterDataStatusDalamProses"
                            @refresh="getData"
                        />
                    </div>
                    <div
                        class="tab-pane fade"
                        id="pills-profile"
                        role="tabpanel"
                        aria-labelledby="pills-profile-tab"
                    >
                        <selesaiproses
                            :selesai="selesaiProsesData"
                            @filter="filterDataStatusSelesaiProses"
                        />
                    </div>
                    <div
                        class="tab-pane fade"
                        id="pills-contact"
                        role="tabpanel"
                        aria-labelledby="pills-contact-tab"
                    >
                        <batalpo :items="batalPoData" @refresh="getData" />
                    </div>
                </div>
            </div>
            <div class="card-body" v-else>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.font-medium {
    font-size: 13px;
}
</style>
