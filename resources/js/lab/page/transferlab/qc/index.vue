<script>
import Header from "../../../components/header.vue";
import Table from "./table.vue";
import pagination from "../../../components/pagination.vue";
import axios from "axios";
import pengujian from './pengujian.vue';
import kalibrasi from "./kalibrasi.vue";
export default {
    components: {
        Header,
        Table,
        pagination,
        pengujian,
        kalibrasi
    },
    data() {
        return {
            title: "Riwayat Transfer QC",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Riwayat Transfer QC",
                    link: "/lab/transfer",
                },
            ],
            dataTable: [],
            riwayat_pengujian: [],
            search: "",
            years: new Date().getFullYear(),
            searchPengujian: '',
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data: tf_riwayat } = await axios.get(
                    "/api/qc/tf_riwayat?years=" + this.years
                );
                const { data: riwayat_pengujian } = await axios.get(
                    "/api/qc/so/riwayat/data?years=" + this.years
                );
                this.riwayat_pengujian = riwayat_pengujian.map((item, index) => {
                    return {
                        no: index + 1,
                        tgl_pengujian: this.formatDate(item.tgl_mulai),
                        tgl_selesai_uji: this.formatDate(item.tgl_selesai),
                        no_so: item.pesanan.so,
                        jenis: item.penjualan_produk ? 'produk' : 'part',
                        ...item,
                    }
                })
                this.dataTable = tf_riwayat.map((item) => {
                    return {
                        ...item,
                        tgl: this.formatDate(item.tgl_transfer),
                        jenis_transfer: 'Internal'
                    }
                });

            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
    },
    computed: {
        // get 5 years from now
        getYear() {
            let year = [];
            for (let i = 0; i < 2; i++) {
                year.push(moment().subtract(i, "years").format("YYYY"));
            }
            return year;
        },
    },
    mounted() {
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
                    role="tab" aria-controls="pills-home" aria-selected="true">Transfer</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Pengujian</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="card">
                    <div class="card-body">
                        <Table :dataTable="dataTable" />
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="card">
                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                    <div class="card-body">
                        <pengujian :produk="riwayat_pengujian" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
