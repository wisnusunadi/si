<script>
import axios from "axios";
import Table from "./table.vue";
import pagination from "../../../components/pagination.vue";
export default {
    components: { Table, pagination },
    data() {
        return {
            produk: [],
            search: "",
            renderPaginate: [],
        };
    },
    methods: {
        async showProduk() {
            try {
                this.$store.dispatch("setLoading", true);
                const { produk } = await axios
                    .get("/api/produk")
                    .then((res) => res.data);
                this.produk = produk;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        refresh() {
            this.showProduk();
        },
    },
    created() {
        this.showProduk();
    },
    computed: {
        filteredDalamProses() {
            return this.produk.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
};
</script>
<template>
    <div class="card" v-if="!$store.state.loading">
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <Table :dataTable="renderPaginate" @refresh="refresh" />
            <pagination
                :filteredDalamProses="filteredDalamProses"
                @updateFilteredDalamProses="updateFilteredDalamProses"
            />
        </div>
    </div>
</template>
