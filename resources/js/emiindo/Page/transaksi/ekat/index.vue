<script>
import batalComponents from "../batal/index.vue";
import returComponents from "../retur.vue";
import detailComponents from "../detail/index.vue";
import doComponents from "../do.vue";
import statusComponents from "../../../components/status.vue";
import pagination from "../../../components/pagination.vue";
import persentase from "../../../components/persentase.vue";
import axios from "axios";

export default {
    components: {
        batalComponents,
        returComponents,
        detailComponents,
        statusComponents,
        doComponents,
        pagination,
        persentase,
    },
    props: ["ekat"],
    data() {
        return {
            header: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "No Urut",
                    value: "urutan",
                },
                {
                    text: "Nomor SO",
                    value: "so",
                },
                {
                    text: "Nomor AKN",
                    value: "no_paket",
                },
                {
                    text: "Nomor PO",
                    value: "no_po",
                },
                {
                    text: "Tanggal Buat",
                    value: "tgl_buat",
                },
                {
                    text: "Tanggal Edit",
                    value: "tgl_edit",
                },
                {
                    text: "Tanggal Delivery",
                    value: "tgl_kontrak",
                },
                {
                    text: "Customer",
                    value: "nama_customer",
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
            search: "",
            showModal: false,
            detailSelected: {},
            status: ["sepakat", "negosiasi", "batal", "draft"],
            renderPaginate: [],
        };
    },
    methods: {
        // tambah data
        tambah() {
            window.location.href = "/penjualanv2/create";
        },
        // fungsi untuk menampilkan modal batal
        batal(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalBatal").modal("show");
            });
        },
        // fungsi untuk menampilkan modal retur
        retur(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalRetur").modal("show");
            });
        },
        // fungsi untuk menampilkan modal detail
        openDO(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDO").modal("show");
            });
        },
        // fungsi untuk menampilkan modal detail
        detail(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
        // fungsi untuk mencetak SPPB
        cetakSPPB(id) {
            window.open(
                `/penjualan/penjualan/cetak_surat_perintah/${id}`,
                "_blank"
            );
        },
        // fungsi untuk menghitung tanggal dari sekarang
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(
                        tglKontrak,
                        "days"
                    )} Hari`,
                    color: "text-danger font-weight-bold",
                    icon: "fas fa-exclamation-circle",
                };
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(
                        tglSekarang,
                        "days"
                    )} Hari Lagi`,
                    color: "text-dark",
                    icon: "fas fa-clock",
                };
            } else {
                return {
                    text: "Batas Kontrak Habis",
                    color: "text-danger",
                    icon: "fas fa-exclamation-circle",
                };
            }
        },
        // fungsi untuk mengubah format tanggal
        filter(year, status) {
            this.$store.dispatch("setYears", year);
            if (status != "") {
                this.$emit("filter", status);
            } else {
                this.$emit("refresh");
            }
        },
        // fungsi untuk mengubah format tanggal
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        // fungsi untuk mengubah format tanggal
        editEkat(pesananId) {
            window.location.href = `/penjualanv2/edit/${pesananId}`;
        },
        // fungsi untuk mengecek apakah value adalah string
        cekIsString(value) {
            if (typeof value === "string") {
                return true;
            } else {
                return false;
            }
        },
        // fungsi untuk menghapus data
        hapus(item) {
            this.$swal({
                title: "Apakah Anda Yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/api/ekatalog/delete/${item.id}`)
                        .then(() => {
                            this.$emit("refresh");
                            this.$swal(
                                "Berhasil!",
                                "Data berhasil dihapus.",
                                "success"
                            );
                        })
                        .catch(() => {
                            this.$swal(
                                "Gagal!",
                                "Data gagal dihapus.",
                                "error"
                            );
                        });
                }
            });
        },
    },
    computed: {
        // fungsionalitas untuk menampilkan data per halaman
        yearsComputed() {
            let years = [];
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, "years").format("YYYY"));
            }
            return years;
        },
        // fungsionalitas untuk menampilkan data per halaman
        filteredDalamProses() {
            const searchKeys = this.header.map(
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

            return this.ekat
                .filter((data) => includesSearch(data, this.search))
                .sort(
                    (a, b) => new Date(b.created_at) - new Date(a.created_at)
                );
        },
    },
};
</script>
<template>
    <div>
        <batalComponents
            v-if="showModal"
            @close="showModal = false"
            :batal="detailSelected"
            @refresh="$emit('refresh')"
        />
        <returComponents
            v-if="showModal"
            @close="showModal = false"
            :retur="detailSelected"
            @refresh="$emit('refresh')"
        />
        <detailComponents
            v-if="showModal"
            @close="showModal = false"
            :detail="detailSelected"
        />
        <doComponents
            v-if="showModal"
            @close="showModal = false"
            :doData="detailSelected"
            @refresh="$emit('refresh')"
        />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <span class="filter">
                            <button
                                class="btn btn-outline-secondary btn-sm"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="fas fa-filter"></i> Filter Tahun
                            </button>
                            <button
                                class="btn btn-outline-info btn-sm"
                                @click="tambah"
                            >
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu" style="">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <div
                                                class="form-check"
                                                v-for="(
                                                    year, key
                                                ) in yearsComputed"
                                                :key="key"
                                            >
                                                <input
                                                    class="form-check-input form-years-select"
                                                    type="radio"
                                                    :value="year"
                                                    :id="`status${key}`"
                                                    @click="filter(year, '')"
                                                    :checked="key == 0"
                                                    v-model="$store.state.years"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    :for="`status${key}`"
                                                >
                                                    {{ year }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_penjualan"
                                                >Status</label
                                            >
                                        </div>
                                        <div class="form-group">
                                            <div
                                                class="form-check"
                                                v-for="(status, key) in status"
                                                :key="key"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    :value="status"
                                                    :id="`status${key}`"
                                                    @click="
                                                        filter(
                                                            $store.state.years,
                                                            status
                                                        )
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    :for="`status${key}`"
                                                >
                                                    {{
                                                        status
                                                            .charAt(0)
                                                            .toUpperCase() +
                                                        status.slice(1)
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
                            v-model="search"
                            placeholder="Cari..."
                        />
                    </div>
                </div>

                <table class="table text-center" v-if="!$store.state.loading">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Urut</th>
                            <th>Nomor SO</th>
                            <th>Nomor AKN</th>
                            <th>Nomor PO</th>
                            <th>Tanggal Buat</th>
                            <th>Tanggal Edit</th>
                            <th>Tanggal Delivery</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr
                            v-for="(item, index) in renderPaginate"
                            :key="index"
                            :class="{
                                'strike-through-row text-danger font-weight-bold':
                                    item.status == 'batal' ||
                                    item.pesanan.log_id == 20,
                            }"
                        >
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ index + 1 }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.urutan }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.so }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.no_paket }}
                                <statusComponents :status="item.status" />
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.no_po }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.tgl_buat }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.tgl_edit }}
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                <div v-if="item.tgl_kontrak_custom">
                                    <div
                                        :class="
                                            calculateDateFromNow(
                                                item.tgl_kontrak_custom
                                            ).color
                                        "
                                    >
                                        {{
                                            dateFormat(item.tgl_kontrak_custom)
                                        }}
                                    </div>
                                    <small
                                        :class="
                                            calculateDateFromNow(
                                                item.tgl_kontrak_custom
                                            ).color
                                        "
                                    >
                                        <i
                                            :class="
                                                calculateDateFromNow(
                                                    item.tgl_kontrak_custom
                                                ).icon
                                            "
                                        ></i>
                                        {{
                                            calculateDateFromNow(
                                                item.tgl_kontrak_custom
                                            ).text
                                        }}
                                    </small>
                                </div>
                                <div v-else></div>
                            </td>
                            <td
                                :class="{
                                    'strike-through':
                                        item.status == 'batal' ||
                                        item.pesanan.log_id == 20,
                                }"
                            >
                                {{ item.nama_customer }}
                            </td>
                            <td>
                                <persentase
                                    :persentase="item.persentase"
                                    v-if="!cekIsString(item.persentase)"
                                />
                                <span class="red-text badge" v-else>{{
                                    item.persentase
                                }}</span>
                            </td>
                            <td>
                                <div>
                                    <div
                                        class="dropdown-toggle"
                                        data-toggle="dropdown"
                                        id="dropdownMenuButton"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <i class="fas fa-ellipsis-v"></i>
                                    </div>
                                    <div
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenuButton"
                                        style=""
                                    >
                                        <button
                                            class="dropdown-item"
                                            type="button"
                                            @click="detail(item)"
                                        >
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                        <button
                                            class="dropdown-item"
                                            type="button"
                                            @click="cetakSPPB(item.pesanan.id)"
                                            v-if="item.no_po != null"
                                        >
                                            <i class="fas fa-print"></i>
                                            SPPB
                                        </button>
                                        <div v-if="item.status != 'batal'">
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                @click="
                                                    editEkat(item.pesanan.id)
                                                "
                                                v-if="
                                                    item.cgudang == 0 ||
                                                    (item.status != 'sepakat' &&
                                                        item.pesanan.log_id !=
                                                            20)
                                                "
                                            >
                                                <i
                                                    class="fas fa-pencil-alt"
                                                ></i>
                                                Edit
                                            </button>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                @click="openDO(item)"
                                                v-if="item.is_editDo == true"
                                            >
                                                <i
                                                    class="fas fa-pencil-alt"
                                                ></i>
                                                Edit No Urut &amp; DO
                                            </button>
                                            <!-- <button
                        class="dropdown-item openModalBatalRetur"
                        v-if="item.pesanan.so == null"
                        @click="hapus(item)"
                        type="button"
                      >
                        <i class="fas fa-trash"></i> Hapus
                      </button> -->
                                            <button
                                                class="dropdown-item openModalBatalRetur"
                                                @click="batal(item)"
                                                v-if="
                                                    item.is_batal &&
                                                    item.pesanan.log_id != 20
                                                "
                                                type="button"
                                            >
                                                <i class="fas fa-times"></i>
                                                Batal
                                            </button>
                                            <button
                                                class="dropdown-item openModalBatalRetur"
                                                v-if="item.is_retur"
                                                @click="retur(item)"
                                                type="button"
                                            >
                                                <i
                                                    class="fa-solid fa-arrow-rotate-left"
                                                ></i>
                                                Retur
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="100%" class="text-center">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-else>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <pagination
                    :filteredDalamProses="filteredDalamProses"
                    v-if="!$store.state.loading"
                    @updateFilteredDalamProses="updateFilteredDalamProses"
                />
            </div>
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
    top: 35%;
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
