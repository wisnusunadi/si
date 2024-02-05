<script>
import pagination from "../../../../../components/pagination.vue";
import axios from "axios";
export default {
    props: ["dataTable"],
    components: {
        pagination,
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
            metode: [],
            ruang: [],
            getMetodeAndRuang: [],
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async getData() {
            try {
                const { data: metode } = await axios.get(
                    "/api/labs/dok"
                );
                this.metode = metode.data.map((item) => {
                    return {
                        id: item.id,
                        label: item.metode,
                    };
                });

                const { data: ruang } = await axios.get(
                    "/api/labs/ruang"
                );
                this.ruang = ruang.data.map((item) => {
                    return {
                        id: item.id,
                        label: item.nama,
                    };
                });

                const { data: ruang_and_metode } = await axios.get(
                    "/api/labs/ruang_and_metode"
                );
                this.getMetodeAndRuang = ruang_and_metode;
            } catch (error) {
                console.log(error);
            }
        },
        getMetode(ruang) {
            if (ruang) {
                const findRuang = []
                this.getMetodeAndRuang.forEach((item) => {
                    if (item.ruang_id === ruang.id) {
                        findRuang.push(item)
                    }
                })
                return findRuang.map((item) => {
                    return {
                        id: item.metode_id,
                        label: item.metode_label,
                        id_detail: item.id
                    };
                });
            } else {
                return this.metode;
            }
        },
        getRuang(metode) {
            if (metode) {
                const findMetode = []
                this.getMetodeAndRuang.forEach((item) => {
                    if (item.metode_id === metode.id) {
                        findMetode.push(item)
                    }
                })
                return findMetode.map((item) => {
                    return {
                        id: item.ruang_id,
                        label: item.ruang_label,
                        id_detail: item.id
                    };
                });
            } else {
                return this.ruang;
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" placeholder="Search" v-model="search" />
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Jumlah Dikalibrasi</th>
                    <th>Metode Kalibrasi</th>
                    <th>Ruangan Kalibrasi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.tipe }}</td>
                    <td>{{ data.noseri?.length }}</td>
                    <td>
                        <v-select v-model="data.metode_id"
                        :options="getMetode(data.ruang_id)" />
                    </td>
                    <td>
                        <v-select v-model="data.ruang_id" :options="getRuang(data.metode_id)" />
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>
