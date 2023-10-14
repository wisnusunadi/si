<script>
import Pagination from '../../../components/pagination.vue';
import Table from './table.vue';
export default {
    components: {
        Pagination,
        Table,
    },
    data() {
        return {
            dataTable: [
                {
                    id: 76,
                    urutan: 1,
                    produk_reworks_id: 329,
                    tgl_mulai: "2023-09-07",
                    tgl_selesai: "2023-09-11",
                    nama: "TAS ANTROPOMETRI KIT-10",
                    jumlah: 5,
                    status: "Proses",
                    belum: 1,
                    selesai: 24,
                    tgl_transfer: "2023-09-11",
                    produk: [
                        {
                            id: 80,
                            produk_id: 188,
                            nama: "MTR-BABY 002",
                            belum: 1,
                            jumlah: 5,
                            noseri: [
                                {
                                    id: 81872,
                                    noseri: "TD17227A00077",
                                    variasi: null
                                },
                                {
                                    id: 81872,
                                    noseri: "TD17227A00077",
                                    variasi: null
                                }
                            ]
                        },
                        {
                            id: 80,
                            produk_id: 188,
                            nama: "MTR-BABY 002",
                            belum: 1,
                            jumlah: 5,
                            noseri: [
                                {
                                    id: 81872,
                                    noseri: "TD17227A00077",
                                    variasi: null
                                }
                            ]
                        }
                    ]
                }
            ],
            search: '',
            renderPaginate: [
            ],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <Table :dataTable="renderPaginate"></Table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>