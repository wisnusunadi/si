<script>
import Header from "../../components/header.vue";
import moment from "moment";
import detail from "./detail.vue";
export default {
    components: {
        Header,
        detail,
    },
    data() {
        return {
            title: "Penerimaan Finish Goods",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Penerimaan Finish Goods",
                    link: "/penerimaan-finish-goods",
                },
            ],
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "Nomor Referensi",
                    value: "no_ref",
                },
                {
                    text: "Tanggal Masuk",
                    value: "datetime",
                },
                {
                    text: "Bagian",
                    value: "bagian",
                },
                {
                    text: "Produk",
                    value: "nama",
                },
                {
                    text: "Jumlah",
                    value: "jumlah",
                },
                {
                    text: "Status",
                    value: "status",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            items: [],
            search: "",
            years: new Date().getFullYear(),
            showModal: false,
            detailSelected: {},
            bagian: null,
            status: null,
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const data = await this.$get(
                    `/api/tfp/rakit?tahun=${this.years}`
                );
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        datetime: this.dateTimeFormat(item.timestamp),
                        ...item,
                    };
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        showDetailModal(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
        statusBadge(status) {
            switch (status) {
                case "produksi":
                    return {
                        text: "Perakitan",
                        color: "badge-primary",
                    };

                case "retur":
                    return {
                        text: "Retur",
                        color: "badge-warning",
                    };

                case "batal":
                    return {
                        // batal mengikuti data noseri dari departemen mana saja
                        text: "Batal",
                        color: "badge-danger",
                    };

                case "peminjaman":
                    return {
                        text: "Peminjaman",
                        color: "badge-info",
                    };

                default:
                    return {
                        text: status,
                        color: "badge-secondary",
                    };
            }
        },
    },
    created() {
        this.getData();
    },
    computed: {
        yearsComputed() {
            let years = [];
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, "years").format("YYYY"));
            }
            return years;
        },
        getBagian() {
            return [...new Set(this.items.map((item) => item.bagian))];
        },
        getStatus() {
            const status = [...new Set(this.items.map((item) => item.status))];
            // change to capitalize
            const capitalize = (s) => {
                if (typeof s !== "string") return "";
                return s.charAt(0).toUpperCase() + s.slice(1);
            };
            // change produksi to perakitan
            return status.map((item) => {
                if (item == "produksi") {
                    return {
                        value: item,
                        label: capitalize("perakitan"),
                    };
                }
                return {
                    value: item,
                    label: capitalize(item),
                };
            });
        },
        // filter items jika bagian dan status sesuai dan tidak kosong, jika kosong maka tampilkan semua    
        filterItems() {
            if (this.items == null && this.bagian == null && this.status == null) {
                return this.items;
            } 

            return this.items.filter((item) => {
                const bagianMatch = this.bagian == null || item.bagian == this.bagian;
                const statusMatch = this.status == null || item.status == this.status.value;
                return bagianMatch && statusMatch;
            })
        },
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <detail
            v-if="showModal"
            @close="showModal = false"
            :detail="detailSelected"
            @refresh="getData"
        />
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-8">
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-fill bd-highlight">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="search"
                                    placeholder="Cari..."
                                />
                            </div>
                            <div class="p-2 flex-fill bd-highlight">
                                <v-select
                                    :options="yearsComputed"
                                    v-model="years"
                                    @input="getData"
                                    placeholder="Pilih Tahun"
                                />
                            </div>
                            <div class="p-2 flex-fill bd-highlight">
                                <v-select
                                    placeholder="Pilih Bagian"
                                    :options="getBagian"
                                    v-model="bagian"
                                />
                            </div>
                            <div class="p-2 flex-fill bd-highlight">
                                <v-select
                                    placeholder="Pilih Status"
                                    :options="getStatus"
                                    v-model="status"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-right"></div>
                </div>
                <data-table
                    :headers="headers"
                    :items="filterItems"
                    :search="search"
                    v-if="!$store.state.loading"
                >
                    <template #item.status="{ item }">
                        <span
                            :class="'badge ' + statusBadge(item.status).color"
                            >{{ statusBadge(item.status).text }}</span
                        >
                    </template>
                    <template #item.aksi="{ item }">
                        <button
                            class="btn btn-outline-primary btn-sm"
                            @click="showDetailModal(item)"
                        >
                            <i class="far fa-edit"></i>
                            Terima
                        </button>
                    </template>
                </data-table>
                <div v-else class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
