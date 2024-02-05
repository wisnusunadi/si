<script>
import hasil from "../../../components/hasil.vue";
export default {
    components: {
        hasil,
    },
    props: ["dataTable"],
    data() {
        return {
            search: "",
            filterHasil: [],
            headers: [
                {
                    text: "No. Seri",
                    value: "no_seri",
                },
                {
                    text: "Tanggal Masuk",
                    value: "tgl_masuk",
                },
                {
                    text: "Hasil",
                    value: "status",
                }
            ]
        };
    },
    methods: {
        clickFilterHasil(filter) {
            if (this.filterHasil.includes(filter)) {
                this.filterHasil = this.filterHasil.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterHasil.push(filter);
            }
        },
        statusText(text) {
            if (typeof text == "string") {
                text = text.toLowerCase();
            }
            switch (text) {
                case "ok":
                    return "lolos kalibrasi";
                    break;
                case "not_ok":
                    return "tidak lolos kalibrasi";
                    break;
                default:
                    return "belum kalibrasi";
                    break;
            }
        },
    },
    computed: {
        filteredDalamProses() {
            let filtered = [];
            if (this.filterHasil.length > 0) {
                this.filterHasil.forEach((filter) => {
                    filtered = filtered.concat(
                        this.dataTable.filter((data) => data.status == filter)
                    );
                });
            } else {
                filtered = this.dataTable;
            }

            return filtered
        },
        getAllStatusUnique() {
            return this.dataTable
                .map((hasil) => {
                    return hasil.status;
                })
                .filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
        },
    },
};
</script>
<template>
    <div>
        <div class="card">
            <img class="card-img-top" src="holder.js/100x180/" alt="" />
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
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
                                                >Keterangan</label
                                            >
                                        </div>
                                        <div
                                            class="form-group"
                                            v-for="status in getAllStatusUnique"
                                            :key="status"
                                        >
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    :ref="status"
                                                    :value="status"
                                                    id="status1"
                                                    @click="
                                                        clickFilterHasil(status)
                                                    "
                                                />
                                                <label
                                                    class="form-check-label text-uppercase"
                                                    for="status1"
                                                >
                                                    {{ statusText(status) }}
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
                            class="form-control my-2"
                            placeholder="Cari..."
                            v-model="search"
                        />
                    </div>
                </div>
                <data-table :headers="headers" :items="filteredDalamProses" :search="search">
                    <template #item.status="{item}">
                        <div>
                            <hasil :hasil="item.status" />
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
<style>
.fa-check {
    color: green;
}
.fa-times {
    color: red;
}
</style>
