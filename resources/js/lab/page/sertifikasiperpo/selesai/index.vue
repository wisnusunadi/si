<script>
import axios from "axios";
import loading from '../../../components/loading.vue';
import modalpage from "../../../components/modalpage.vue";
export default {
    components: {
        loading,
        modalpage,
    },
    data() {
        return {
            search: "",
            dataTable: [],
            filterdalamProses: [],
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'pemilik_sertif'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false
                }
            ],
            modal: false,
            dataCetak: null,
        };
    },
    methods: {
        clickFilterdalamProses(filter) {
            if (this.filterdalamProses.includes(filter)) {
                this.filterdalamProses = this.filterdalamProses.filter(item => item !== filter)
            } else {
                this.filterdalamProses.push(filter)
            }
        },
        async getDataSelesai() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/data/semua");
                this.dataTable = data.data
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        detail(id) {
            this.$router.push({ name: 'detail-sertifikasiperpo', params: { id: id, history: this.$route.path } });
        },
        cetakSertifikat(id, ttd, jenis = 'po') {
            this.modal = true;
            this.dataCetak = {
                id,
                ttd,
                jenis,
            };
            this.$nextTick(() => {
                $(".modalPage").modal("show");
            });
        },
    },
    computed: {
        filteredDalamProses() {
            let filtered = []
            if (this.filterdalamProses.length > 0) {
                this.filterdalamProses.forEach((filter) => {
                    filtered = filtered.concat(this.dataTable.filter((data =>
                        data.keterangan == filter)))
                })
            } else {
                filtered = this.dataTable
            }

            return filtered
        },
        getAllStatusUnique() {
            return this.dataTable.map((sertifikasi) => {
                return sertifikasi.keterangan
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    },
    created() {
        this.getDataSelesai();
    },
};
</script>
<template>
    <div v-if="!$store.state.loading">
        <modalpage v-if="modal" @closeModal="modal = false" :dataCetak="dataCetak" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <!-- <span class="float-left filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                            id="status1" @click="clickFilterdalamProses(status)" />
                                        <label class="form-check-label text-uppercase" for="status1">
                                            {{ status }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span> -->
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
            </div>
        </div>
        <data-table :headers="headers" :items="filteredDalamProses" :search="search">
            <template #item.aksi="{ item }">
                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                    aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                    <a class="dropdown-item" @click="detail(item.id)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </a>
                    <button class="dropdown-item" type="button" @click="cetakSertifikat(item.id, ttd = false)">
                        <i class="fas fa-file"></i>
                        Sertifikasi
                    </button>
                    <button class="dropdown-item" type="button" @click="cetakSertifikat(item.id, ttd = true)">
                        <i class="fas fa-file"></i>
                        Sertifikasi + TTD
                    </button>
                </div>
            </template>
        </data-table>

    </div>
</template>
