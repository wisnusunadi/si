<script>
import axios from "axios";
export default {
    props: ["dataTable"],
    data() {
        return {
            search: "",
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "Nama Barang",
                    value: "nama",
                },
                {
                    text: "Tipe",
                    value: "tipe",
                },
                {
                    text: "Jml Dikalibrasi",
                    value: "noseri",
                },
                {
                    text: "Metode Kalibrasi",
                    value: "metode_id",
                    width: "25%",
                },
                {
                    text: "Ruang Kalibrasi",
                    value: "ruang_id",
                }
            ],
            getMetodeAndRuang: [],
        };
    },
    methods: {
        async getData() {
            try {
                const { data: ruang_and_metode } = await axios.get(
                    "/api/labs/ruang_and_metode"
                );

                // sorted by metode label ascending

                this.getMetodeAndRuang = ruang_and_metode.slice().sort((a, b) => {
                    if (a.metode_label < b.metode_label) {
                        return -1;
                    }
                    if (a.metode_label > b.metode_label) {
                        return 1;
                    }
                    return 0;
                }).map((item) => {
                    return {
                        id: item.id,
                        metode_id: item.metode_id,
                        ruang_id: item.ruang_id,
                        ruang_label: item.ruang_label,
                        label: item.metode_label,
                    };
                });

            } catch (error) {
                console.log(error);
            }
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
        <data-table :headers="headers" :items="dataTable" :search="search">
            <template #item.no="{ item, index }">
                {{ index + 1 }}
            </template>
            <template #item.noseri="{ item }">
                {{ item.noseri?.length }}
            </template>
            <template #item.metode_id="{ item }">
                <v-select v-model="item.metode_id" transition="" :options="getMetodeAndRuang" />
            </template>
            <template #item.ruang_id="{ item }">
                <span>{{ item?.metode_id?.ruang_label }}</span>
            </template>
        </data-table>
    </div>
</template>
