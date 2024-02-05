<script>
import Header from "../../components/header.vue";
import loading from "../../components/loading.vue";
import axios from 'axios';
export default {
    components: {
        Header,
        loading,
    },
    data() {
        return {
            title: "MONITORING KALIBRASI",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Monitoring Kalibrasi",
                    link: "/kalibrasi",
                },
            ],
            search: "",
            renderPaginate: [],
            dataTable: [],
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "No SO",
                    value: "so",
                },
                {
                    text: "No PO",
                    value: "po",
                },
                {
                    text: "Customer",
                    value: "customer",
                },
                {
                    text: "Status",
                    value: "status",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                }
            ]
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get('/api/qc/monitoring').then(res => res.data)
                this.dataTable = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        detail(id) {
            this.$router.push({ name: "monitoring-detail", params: { id: id } });
        }
    },
    created() {
        this.getData()
    },
};
</script>
<template>
    <div v-if="!$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        </div>
                    </div>
                </div>
                <data-table :headers="headers" :items="dataTable" :search="search">
                    <template #item.status="{item}">
                        <loading :persentase="item.status" />
                    </template>
                    <template #item.aksi="{item}">
                        <button class="btn-sm btn-outline-primary btn" @click="detail(item.id)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
