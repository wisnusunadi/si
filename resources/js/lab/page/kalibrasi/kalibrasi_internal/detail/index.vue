<script>
import Header from "../../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Produk from "./produk.vue";
import axios from "axios";
export default {
    components: {
        Header,
        HeaderDetail,
        Produk,
    },
    data() {
        return {
            title: "Detail Kalibrasi",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Kalibrasi",
                    link: "/lab/kalibrasi",
                },
                {
                    name: "Detail Kalibrasi",
                    link: "/lab/kalibrasi/detail",
                },
            ],
            dataTable: [],
            header: [],
        };
    },
    methods: {
        async getDataDetail() {
            try {
                this.$store.dispatch("setLoading", true);
                const id = this.$route.params.id;
                const { data } = await axios.get(`/api/labs/kalibrasi/${id}`).then(res => res.data);
                if (data.length == 0) {
                    this.$router.push("/lab/kalibrasi");
                }
                this.header = data?.header;
                this.dataTable = data?.produk
                
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
    },
    created() {
        this.getDataDetail();
    },
};
</script>
<template>
    <div v-if="!$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <HeaderDetail :header="header" />
        <Produk :dataTable="dataTable" :header="header" @refresh="getDataDetail"/>
    </div>
</template>
