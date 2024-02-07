<script>
import produk from "./produk.vue";
export default {
    components: {
        produk,
    },
    data() {
        return {
            modal: false,
            selectedProduk: null,
            headers: [
                {
                    text: "No SO",
                    value: "so",
                },
                {
                    text: "No PO",
                    value: "no_po"
                },
                {
                    text: "Customer",
                    value: "customer",
                },
                {
                    text: "Tanggal Transfer",
                    value: "tgl",
                    sortable: false,
                },
                {
                    text: "Jenis Transfer",
                    value: "jenis_transfer",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                }
            ],
            tanggalAwal: '',
            tanggalAkhir: '',
        }
    },
    props: ["dataTable", 'search'],
    methods: {
        detailProduk(data) {
            this.modal = true;
            this.selectedProduk = data;
            this.$nextTick(() => {
                $(".modalRiwayatProduk").modal("show");
            });
        },
    },
    computed: {
        filterData() {
            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date >= startDate
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date <= endDate
                })
            }

            return this.dataTable
        },
    }
};
</script>
<template>
    <div>
        <produk v-if="modal" @close="modal = false" :headerSO="selectedProduk" />
        <data-table :headers="headers" :items="filterData" :search="search" v-if="!$store.state.loading">
            <template #header.tgl>
                <span class="text-bold pr-2">Tanggal Transfer</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalAwal"
                                                :max="tanggalAkhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhir"
                                                :min="tanggalAwal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-primary" @click="detailProduk(item)">
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
        <div class="spinner-border spinner-border-sm" role="status" v-else>
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>
