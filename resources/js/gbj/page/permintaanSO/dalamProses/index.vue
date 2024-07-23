<script>
import detail from "./modalDetail.vue";
import pagination from "../../../../emiindo/components/pagination.vue";
export default {
    components: {
        detail,
        pagination,
    },
    props: ["items", "prosesSelected"],
    data() {
        return {
            search: "",
            localProsesSelected: this.prosesSelected,
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "Nomor SO",
                    value: "so",
                },
                {
                    text: "Nomor PO",
                    value: "no_po",
                },
                {
                    text: "Customer",
                    value: "divisi",
                },
                {
                    text: "Batas Transfer",
                    value: "batas_transfer",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            showModal: false,
            detailSelected: {},
            filterData: [
                {
                    label: "Semua",
                    value: "semua",
                },
                {
                    label: "Dalam Proses",
                    value: "proses",
                },
                {
                    label: "Batal PO",
                    value: "batal",
                },
            ],
            renderPaginate: [],
        };
    },
    methods: {
        showDetail(item) {
            this.detailSelected = {
                detailOpen: true,
                ...item,
            };
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
        showSiapkanProduk(item) {
            this.detailSelected = {
                detailOpen: false,
                ...item,
            };
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
        cetakSPPB(id) {
            window.open(
                `/penjualan/penjualan/cetak_surat_perintah/${id}`,
                "_blank"
            );
        },
        refresh() {
            this.$emit("refresh");
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    watch: {
        localProsesSelected() {
            this.$emit("filter", this.localProsesSelected);
        },
    },
    computed: {
        filteredDalamProses() {
            const searchKeys = this.headers.map(
                (headerItem) => headerItem.value
            );

            const includesSearch = (obj, search) => {
                if (obj && typeof obj === "object") {
                    return searchKeys.some((key) => {
                        if (typeof obj[key] === "object") {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key])
                            .toLowerCase()
                            .includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.items.filter((item) =>
                includesSearch(item, this.search)
            );
        },
    },
};
</script>
<template>
    <div class="card">
        <detail
            v-if="showModal"
            :detailSelected="detailSelected"
            @closeModal="showModal = false"
            @refresh="refresh"
        />
        <div class="card-body">
            <div class="d-flex bd-highlight mb-3">
                <div class="p-2 bd-highlight">
                    <v-select
                        v-model="localProsesSelected"
                        :options="filterData"
                        style="width: 200px"
                    ></v-select>
                </div>
                <div class="ml-auto p-2 bd-highlight">
                    <input
                        type="text"
                        class="form-control"
                        v-model="search"
                        placeholder="Cari..."
                    />
                </div>
            </div>

            <table class="table text-center">
                <thead>
                    <tr>
                        <th v-for="header in headers" :key="header.value">
                            {{ header.text }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in renderPaginate"
                        :key="item.id"
                        :class="{
                            'strike-through-row': item.is_batal == 'true',
                        }"
                    >
                        <td
                            :class="{
                                'strike-through text-danger font-weight-bold':
                                    item.is_batal == 'true',
                            }"
                        >
                            {{ index + 1 }}
                        </td>
                        <td
                            :class="{
                                'strike-through text-danger font-weight-bold':
                                    item.is_batal == 'true',
                            }"
                        >
                            {{ item.so }}
                        </td>
                        <td
                            :class="{
                                'strike-through text-danger font-weight-bold':
                                    item.is_batal == 'true',
                            }"
                        >
                            {{ item.no_po }}
                        </td>
                        <td
                            :class="{
                                'strike-through text-danger font-weight-bold':
                                    item.is_batal == 'true',
                            }"
                        >
                            {{ item.divisi }}
                        </td>
                        <td
                            :class="{
                                'strike-through text-danger font-weight-bold':
                                    item.is_batal == 'true',
                            }"
                        >
                            {{ item.batas_transfer }}
                        </td>
                        <td>
                            <div>
                                <button
                                    class="btn btn-outline-success btn-sm"
                                    @click="showDetail(item)"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                                <button
                                    v-if="item.is_batal != 'true'"
                                    class="btn btn-sm btn-outline-primary"
                                    @click="showSiapkanProduk(item)"
                                >
                                    <i class="fas fa-plus"></i>
                                    Siapkan Produk
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    v-if="
                                        item.no_po != null &&
                                        item.tgl_po != null &&
                                        item.is_batal != 'true'
                                    "
                                    @click="cetakSPPB(item.id)"
                                >
                                    <i class="fas fa-print"></i>
                                    SPPB
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination
                :filteredDalamProses="filteredDalamProses"
                v-if="!$store.state.loading"
                @updateFilteredDalamProses="updateFilteredDalamProses"
            />
        </div>
    </div>
</template>
<style>
.strike-through-row .strike-through {
    position: relative;
}

.strike-through-row .strike-through::before {
    content: "";
    position: absolute;
    top: 40%;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: red;
}

.strike-through-row .strike-through td {
    position: relative;
    z-index: 2;
}
</style>
