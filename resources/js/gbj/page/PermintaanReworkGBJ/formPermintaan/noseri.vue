<script>
import pagination from "../../components/pagination.vue";
export default {
    components: {
        pagination,
    },
    props: ['produk', 'jumlah'],
    data() {
        return {
            search: '',
            renderPaginate: [],
            noseri: [
                {
                    id: 1,
                    no_seri: 'TD-001',
                },
                {
                    id: 2,
                    no_seri: 'TD-002',
                },
                {
                    id: 3,
                    no_seri: 'TD-003',
                }
            ]
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $(".modalSeri").modal("hide");
            this.$emit("close");
        },
        selectNoSeri(noseri) {
            if (this.produk.noseri) {
                if (this.produk.noseri.find((data) => data.no_seri === noseri.no_seri)) {
                    this.produk.noseri = this.produk.noseri.filter(
                        (data) => data.no_seri !== noseri.no_seri
                    );
                    this.$refs.checkAll.checked = false;
                } else {
                    this.produk.noseri.push(noseri);
                }
            } else {
                this.produk.noseri = [];
                this.produk.noseri.push(noseri);
            }

            if (this.produk.noseri.length === this.noseri.length) {
                this.$refs.checkAll.checked = true;
            }
        },
        checkAll() {
            if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.produk.noseri = [];
                    this.$refs.noseri.forEach((data) => {
                        data.checked = false;
                    });
                } else {
                    this.produk.noseri = [];
                    this.noseri.forEach((data) => {
                        this.produk.noseri.push(data);
                    });
                    this.$refs.noseri.find((data) => {
                        data.checked = true;
                    });
                }
            } else {
                this.produk.noseri = [];
                this.noseri.forEach((data) => {
                    this.produk.noseri.push(data);
                });
                this.$refs.noseri.forEach((data) => {
                    data.checked = true;
                });
            }
        },
        checkNoSeriCheckAll() {
            if (this.produk.noseri) {
                if (this.produk.noseri.length === this.noseri.length) {
                    this.$refs.checkAll.checked = true;
                } else {
                    this.$refs.checkAll.checked = false;
                }
            } else {
                this.$refs.checkAll.checked = false;
            }
        },
        simpan() {
            if (!this.produk.noseri || this.produk.noseri.length === 0) {
                this.$swal(
                    "Peringatan",
                    "Pilih nomor seri terlebih dahulu",
                    "warning"
                );
                return;
            }

            if (this.produk.noseri.length > this.jumlah) {
                this.$swal(
                    "Peringatan",
                    "Jumlah nomor seri tidak boleh lebih dari jumlah produk",
                    "warning"
                );
                return;
            }
            this.$emit("simpan", this.produk);
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

    </div>
</template>