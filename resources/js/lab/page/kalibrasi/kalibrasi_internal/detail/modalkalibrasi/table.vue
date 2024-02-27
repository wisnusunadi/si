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
                this.getMetodeAndRuang = ruang_and_metode.map((item) => {
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
        // getRuang(metode) {
        //     if (metode) {
        //         const findMetode = []
        //         this.getMetodeAndRuang.forEach((item) => {
        //             if (item.metode_id === metode.id) {
        //                 findMetode.push(item)
        //             }
        //         })
        //         return findMetode.map((item) => {
        //             return {
        //                 id: item.ruang_id,
        //                 label: item.ruang_label,
        //                 id_detail: item.id
        //             };
        //         });
        //     } else {
        //         return this.ruang;
        //     }
        // }
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
