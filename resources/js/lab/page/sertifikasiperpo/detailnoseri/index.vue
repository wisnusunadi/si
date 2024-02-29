<script>
import Header from "../../../components/header";
import axios from "axios";
import Hasil from "../../../components/hasil.vue";
import modalpage from "../../../components/modalpage.vue";
export default {
    components: {
        Header,
        Hasil,
        modalpage,
    },
    data() {
        return {
            title: "DETAIL SERTIFIKASI PER NOMOR ORDER - DETAIL PER BARANG",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Sertifikasi",
                    link: this.$route.params.history,
                },
                {
                    name: "Detail Sertifikasi Per Barang",
                    link: "/lab/sertifikasiperpo/detailnoseri",
                },
            ],
            detailSeri: null,
            detailNoSeri: [],
            search: "",
            headers: [
                {
                    text: "No Urut",
                    value: "no",
                },
                {
                    text: "Nomor Seri",
                    value: "no_seri",
                },
                {
                    text: "Tgl Masuk",
                    value: "tgl_masuk",
                },
                {
                    text: "Teknisi",
                    value: "teknisi",
                },
                {
                    text: "R. Kalibrasi",
                    value: "ruang_kalibrasi",
                },
                {
                    text: "Metode Kalibrasi",
                    value: "metode",
                },
                {
                    text: "Nomor Sertifikat",
                    value: "nomor_sertifikat",
                },
                {
                    text: "Tanggal Sertifikat",
                    value: "tgl_sertif",
                },
                {
                    text: "Akhir Sertifikat",
                    value: "akhir_sertifikat",
                },
                {
                    text: "Hasil",
                    value: "hasil",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            modal: false,
            dataCetak: null,
        };
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(`/api/labs/data/seri/${this.$route.params.id}`).then((res) => res.data);
                this.detailSeri = data
                if (data.seri) {
                    this.detailNoSeri = data?.seri.map((item) => {
                        return {
                            ...item,
                            tgl_masuk: this.formatDate(item.tgl_masuk),
                            tgl_sertif: this.formatDate(item.tgl_sertif),
                            akhir_sertifikat: this.formatDate(item.akhir_sertifikat),
                        }
                    })
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        cetakSertifikat(id, ttd, jenis = 'seri') {
            this.modal = true;
            this.dataCetak = {
                id,
                ttd,
                jenis,
            };
            this.modal = true;
            this.$nextTick(() => {
                $(".modalPage").modal("show");
            });
        },
    },
    created() {
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
                <div class="text-center text-uppercase">
                    <h5 class="text-bold">
                        detail produk - {{ detailSeri?.nama }} -
                        {{ detailSeri?.tipe }}
                    </h5>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                    </div>
                </div>
                <data-table :headers="headers" :items="detailNoSeri" :search="search">
                    <template #item.hasil="{ item }">
                        <hasil :hasil="item.hasil" />
                    </template>
                    <template #item.aksi="{ item }">
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
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
