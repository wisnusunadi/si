<script>
import pagination from '../../../../components/pagination.vue';
import modal from "./modal.vue";
export default {
    components: {
        pagination,
        modal,
    },
    data() {
        return {
            riwayatproduk: [
                {
                    nama_produk: "Produk 1",
                    jumlah_pakai: 5,
                },
            ],
            noseri: [
                {
                    noseri: "TD2344532",
                    waktu_pakai: "2023-12-12",
                },
                {
                    noseri: "TD2344532",
                    waktu_pakai: "2023-12-12",
                },
                {
                    noseri: "TD2344532",
                    waktu_pakai: "2023-12-12",
                },
                {
                    noseri: "TD2344532",
                    waktu_pakai: "2023-12-12",
                },
                {
                    noseri: "TD2344532",
                    waktu_pakai: "2023-12-12",
                }
            ],
            modal: false,
            search: "",
            renderPaginate: [],
            years_selected: new Date().getFullYear(),
        };
    },
    methods: {
        clickFilterYear(year) {
            this.years_selected = year;
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        detail(id) {
            this.modal = true;
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show');
            });
        },
    },
    computed: {
        filteredDalamProses() {
            return this.riwayatproduk.filter((item) => {
                return Object.keys(item).some((key) => {
                    return String(item[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    }
};
</script>
<template>
    <div>
        <modal v-if="modal" :formDetailSeri="noseri" @close="modal = false" />
        <div class="d-flex bd-highlight mb-3">
            <div class="mr-auto p-2 bd-highlight">
                <span class="float-left filter">
                    <button
                        class="btn btn-outline-info"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan"
                                        >Tahun Pemakaian</label
                                    >
                                </div>
                                <div
                                    class="form-group"
                                    v-for="year in 6"
                                    :key="year"
                                    :value="new Date().getFullYear() - year + 1"
                                >
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            :value="
                                                years_selected ==
                                                new Date().getFullYear() -
                                                    year +
                                                    1
                                            "
                                            id="status1"
                                            :checked="
                                                years_selected ==
                                                new Date().getFullYear() -
                                                    year +
                                                    1
                                            "
                                            @click="
                                                clickFilterYear(
                                                    new Date().getFullYear() -
                                                        year +
                                                        1
                                                )
                                            "
                                        />
                                        <label
                                            class="form-check-label text-uppercase"
                                            for="status1"
                                        >
                                            {{
                                                new Date().getFullYear() -
                                                year +
                                                1
                                            }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari"
                    v-model="search"
                />
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Pakai</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(item, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama_produk }}</td>
                    <td>{{ item.jumlah_pakai }}</td>
                    <td>
                        <button class="btn btn-outline-info" @click="detail(index)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="3" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination
                :filteredDalamProses="filteredDalamProses"
                @updateFilteredDalamProses="updateFilteredDalamProses"
            />
    </div>
</template>
