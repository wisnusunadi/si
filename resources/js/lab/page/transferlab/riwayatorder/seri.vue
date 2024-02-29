<script>
import Hasil from "../../../components/hasil.vue";
export default {
    components: {
        Hasil,
    },
    props: ["noseri"],
    data() {
        return {
            search: "",
            filterHasil: [],
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'no_seri'
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tanggal'
                },
                {
                    text: 'Penguji',
                    value: 'penguji'
                },
                {
                    text: 'Hasil',
                    value: 'hasil'
                },
            ],
        }
    },
    methods: {
        closeModal() {
            $(".modalRiwayatSeri").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
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
                case "lolos_pengujian":
                    return "lolos pengujian";
                    break;
                case "tidak_lolos_pengujian":
                    return "tidak lolos pengujian";
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
                        this.noseri.filter((item) => item.hasil == filter)
                    );
                });
            } else {
                filtered = this.noseri;
            }
            return filtered.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                };
            });
        },
        getAllStatusUnique() {
            return [
                ...new Set(this.noseri.map((item) => item.hasil))]
        }
    }
}
</script>
<template>
    <div class="modal fade modalRiwayatSeri" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                                    <input class="form-check-input" type="checkbox" :ref="status"
                                                        :value="status" id="status1" @click="
                                                            clickFilterHasil(
                                                                status
                                                            )
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
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                        </div>
                    </div>
                    <data-table :headers="headers" :items="filteredDalamProses" :search="search">
                        <template #item.hasil="{ item }">
                            <div>
                                <hasil :hasil="item.hasil" />
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>