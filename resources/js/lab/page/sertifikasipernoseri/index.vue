<script>
import Header from "../../components/header.vue";
import modalpage from "../../components/modalpage.vue";
import axios from "axios";
export default {
    components: {
        Header,
        modalpage,
    },
    data() {
        return {
            title: "SERTIFIKASI PER NOMOR KALIBRASI",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Sertifikasi Per No Kalibrasi",
                    link: "/sertifikasipernoseri",
                },
            ],
            search: "",
            dataTable: [],
            headers: [
                {
                    text: 'No Urut',
                    value: 'no_urut'
                },
                {
                    text: 'Nomor Sertifikat',
                    value: 'kode'
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'No Order',
                    value: 'order'
                },
                {
                    text: 'Distributor',
                    value: 'nama'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'nama_pemilik_sertifikat'
                },
                {
                    text: 'Tgl Kalibrasi',
                    value: 'tgl',
                    sortable: false
                },
                {
                    text: 'Batas Akhir Kalibrasi',
                    value: 'batas_akhir_kalibrasi'
                },
                {
                    text: 'Teknisi',
                    value: 'teknisi'
                },
                {
                    text: 'Hasil Kalibrasi',
                    value: 'hasil_kalibrasi'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false
                }
            ],
            modal: false,
            dataCetak: null,
            tanggalAwal: '',
            tanggalAkhir: ''
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/sertif").then((res) => res.data);
                this.dataTable = data.map((item, index) => {
                    return {
                        ...item,
                        no_urut: index + 1,
                        tgl: this.formatDate(item.tgl_kalibrasi),
                        batas_akhir_kalibrasi: this.formatDate(item.tgl_kalibrasi_exp),
                    }
                })
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        detail(id) {
            // get router now
            this.$router.push({ name: 'detailsertifikasipernoseri', params: { id: id, history: this.$route.path } })
        },
        cetakSertifikat(id, ttd, jenis = 'seri') {
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
        filterData() {
            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_kalibrasi)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_kalibrasi)
                    return date >= startDate
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter(item => {
                    const date = new Date(item.tgl_kalibrasi)
                    return date <= endDate
                })
            }

            return this.dataTable
        }
    },
    mounted() {
        this.getData();
    },
};
</script>
<template>
    <div v-if="!$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <modalpage v-if="modal" @closeModal="modal = false" :dataCetak="dataCetak" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                    </div>
                </div>
                <data-table :headers="headers" :items="filterData" :search="search">
                    <template #header.tgl>
                        <span class="text-bold pr-2">Tgl Kalibrasi</span>
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
                    <template #item.hasil_kalibrasi="{ item }">
                        <i v-if="item.hasil == 'ok'" class="fas fa-check-circle text-success"></i>
                        <i v-else class="fas fa-times-circle text-danger"></i>
                    </template>
                    <template #item.aksi="{ item }">
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
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
        </div>
    </div>
</template>
