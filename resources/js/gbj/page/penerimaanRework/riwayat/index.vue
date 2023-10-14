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
                    tgl_terima: "2023-09-11",
                    noseri: [
                        {
                            id: 195516,
                            noseri: "AK1023A000003",
                            tgl_buat: "2023-10-13",
                            packer: null,
                            seri: [
                                {
                                    id: 7079,
                                    noseri: "TD1621AA8268",
                                    varian: "",
                                    produk: "MTB-2MTR"
                                },
                                {
                                    id: 26885,
                                    noseri: "TD09202B0484",
                                    varian: "COKLAT",
                                    produk: "DIGIT PRO IDA"
                                },
                                {
                                    id: 81872,
                                    noseri: "TD17227A00077",
                                    varian: "",
                                    produk: "MTR-BABY 002"
                                },
                                {
                                    id: 114347,
                                    noseri: "TD15229A0049",
                                    varian: "",
                                    produk: "PTB-2in1 "
                                },
                                {
                                    id: 166263,
                                    noseri: "TD21237A00153",
                                    varian: "",
                                    produk: "DIGIT-PRO BABY"
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