<script>
import modalchecked from "./modalchecked.vue";
import Hasil from "../../../../components/hasil.vue";
export default {
    components: {
        modalchecked,
        Hasil,
    },
    props: ["no_seri", "dataTableSelected"],
    data() {
        return {
            search: "",
            checkallvalue: false,
            showmodal: false,
            filterHasil: [],
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No Urut',
                    value: 'no_urut'
                },
                {
                    text: 'No Seri',
                    value: 'no_seri'
                },
                {
                    text: 'Tanggal Masuk',
                    value: 'tgl'
                },
                {
                    text: 'status',
                    value: 'status'
                }
            ],
            loading: false,
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
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        checkselect(data) {
            if (this.dataTableSelected.noseri == undefined) {
                this.dataTableSelected.noseri = [];
            }
            if (this.dataTableSelected.noseri.find((x) => x.id == data.id)) {
                this.dataTableSelected.noseri.splice(
                    this.dataTableSelected.noseri.indexOf(data),
                    1
                );
            } else {
                // cek apakah noseri sudah ada atau belum
                if (
                    !this.dataTableSelected.noseri.find(
                        (item) => item.id == data.id
                    )
                ) {
                    this.dataTableSelected.noseri.push(data);
                }
            }

            if (this.dataTableSelected.noseri.length == this.no_seri.length) {
                this.checkallvalue = true;
            } else {
                this.checkallvalue = false;
            }
            this.$emit("checked");
        },
        checkall() {
            this.checkallvalue = !this.checkallvalue;
            if (this.dataTableSelected.noseri == undefined) {
                this.dataTableSelected.noseri = [];
            }
            if (this.dataTableSelected.noseri.length == this.no_seri.length) {
                this.dataTableSelected.noseri = [];
            } else {
                this.dataTableSelected.noseri = [];
                for (let i = 0; i < this.no_seri.length; i++) {
                    // cek apakah noseri sudah ada atau belum
                    if (
                        !this.dataTableSelected.noseri.find(
                            (item) => item.id == this.no_seri[i].no_seri
                        )
                    ) {
                        this.dataTableSelected.noseri.push(this.no_seri[i]);
                    }
                }
            }
            this.$emit("checked");
        },
        checked(data) {
            if (this.dataTableSelected.noseri && this.dataTableSelected.noseri.find((x) => x.id == data.id)) {
                return true;
            }
            return false;
        },
        showNoSeriText() {
            this.showmodal = true;
            this.$nextTick(() => {
                $(".modalChecked").modal("show");
            });
        },
        submit(noseri) {
            this.loading = true;
            // split noseri by comma or space or tab
            if (this.dataTableSelected.noseri == undefined) {
                this.dataTableSelected.noseri = [];
            }
            let noserinotfound = [];

            let noseriarray = noseri.split(/[\n, \t]/);

            noseriarray = noseriarray.filter((item) => item != "");

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.no_seri.length; j++) {
                    if (this.no_seri[j].no_seri == noseriarray[i]) {
                        if (!this.dataTableSelected.noseri.find((x) => x.id == this.no_seri[j].id)) {
                            this.dataTableSelected.noseri.push(this.no_seri[j]);
                        }
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0 && noserinotfound !== "") {
                this.$swal('Peringatan', "Nomor seri " +
                    (noserinotfound.length > 1
                        ? noserinotfound.slice(0, 1).join(", ") + " ... dan " + (noserinotfound.length - 1) + " lainnya"
                        : noserinotfound.join(", ")) +
                    " tidak ditemukan", 'warning')
            }
            if (this.dataTableSelected.noseri.length == this.no_seri.length) {
                this.checkallvalue = true;
            } else {
                this.checkallvalue = false;
            }

            this.$nextTick(() => {
                this.loading = false;
                this.$emit("checked");
            });
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
            let filtered = this.no_seri;

            if (this.filterHasil.length > 0) {
                filtered = filtered.filter((hasil) => {
                    return this.filterHasil.includes(hasil.status);
                });
            }

            return filtered;
        },
        getAllStatusUnique() {
            return this.no_seri
                .map((hasil) => {
                    return hasil.status;
                })
                .filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
        },
        cekStatus() {
            let show = true;
            if (this.no_seri) {
                this.no_seri.forEach((data) => {
                    if (data.status != "belum") {
                        show = false;
                    }
                });
            }
            return show;
        }
    },
    created() {
        if (this.dataTableSelected.noseri) {
            if (this.dataTableSelected.noseri.length == this.no_seri.length) {
                this.checkallvalue = true;
            } else {
                this.checkallvalue = false;
            }
        }
    },
};
</script>
<template>
    <div class="card">
        <modalchecked v-if="showmodal" @close="showmodal = false" @submit="submit" />
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <span class="float-left filter">
                        <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <form id="filter_ekat">
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Keterangan</label>
                                    </div>
                                    <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :ref="status" :value="status"
                                                id="status1" @click="
                                                    clickFilterHasil(status)
                                                    " />
                                            <label class="form-check-label text-uppercase" for="status1">
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
                    <button class="btn btn-sm btn-primary" @click="showNoSeriText">
                        Pilih No Seri Via Text
                    </button>
                </div>
            </div>
            <input type="text" class="form-control my-2" placeholder="Cari..." v-model="search" />
            <div class="spinner-border" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="scrollable" v-else>
                <data-table :headers="headers" :items="filteredDalamProses" :search="search">
                    <template #header.id>
                        <input type="checkbox" @click="checkall" :checked="checkallvalue" v-if="cekStatus" />
                    </template>

                    <template #header.status>
                        <span class="text-bold pr-2">Hasil</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Keterangan</label>
                                        </div>
                                        <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :ref="status"
                                                    :value="status" id="status1" @click="
                                                        clickFilterHasil(status)
                                                        " />
                                                <label class="form-check-label text-uppercase" for="status1">
                                                    {{ statusText(status) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #item.id="{ item }">
                        <div>
                            <input type="checkbox" @click="checkselect(item)" :checked="checked(item)"
                                v-if="item.status == 'belum'" />
                        </div>
                    </template>
                    <template #item.status="{ item }">
                        <hasil :hasil="item.status" />
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-y: auto;
    max-height: 300px;
    height: 200px;
}
</style>
