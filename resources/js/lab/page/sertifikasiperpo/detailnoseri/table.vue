<script>
import Hasil from "../../../components/hasil.vue";
import modalpage from "../../../components/modalpage.vue";
export default {
    components: {
        Hasil,
        modalpage,
    },
    data() {
        return {
            modal: false,
            dataCetak: null,
        }
    },
    props: ["dataTable"],
    methods: {
        cetakSertifikat(id, ttd) {
            this.modal = true;
            this.dataCetak = {
                id,
                ttd,
                jenis: 'seri',
            };
            this.modal = true;
            this.$nextTick(() => {
                $("#modelId").modal("show");
            });
        },
    },
};
</script>
<template>
    <div>
        <modalpage v-if="modal" @closeModal="modal = false" :dataCetak="dataCetak"/>
        <table class="table">
            <thead>
                <tr>
                    <th>No Urut</th>
                    <th>Nomor Seri</th>
                    <th>Tgl Masuk</th>
                    <th>Teknisi</th>
                    <th>R. Kalibrasi</th>
                    <th>Metode Kalibrasi</th>
                    <th>Nomor Sertifikat</th>
                    <th>Tanggal Sertifikat</th>
                    <th>Akhir Sertifikat</th>
                    <th>Hasil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.no }}</td>
                    <td>{{ data.no_seri }}</td>
                    <td>{{ formatDate(data.tgl_masuk) }}</td>
                    <td>{{ data.teknisi }}</td>
                    <td>{{ data.ruang_kalibrasi }}</td>
                    <td>{{ data.metode }}</td>
                    <td>{{ data.nomor_sertifikat }}</td>
                    <td>{{ formatDate(data.tgl_sertif) }}</td>
                    <td>{{ formatDate(data.akhir_sertifikat) }}</td>
                    <td>
                        <Hasil :hasil="data.hasil" />
                    </td>
                    <td>
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
                                @click="cetakSertifikat(data.id, ttd = false)"
                            >
                                <i class="fas fa-file"></i>
                                Sertifikasi
                            </button>
                            <button
                                class="dropdown-item"
                                type="button"
                                @click="cetakSertifikat(data.id, ttd = true)"
                            >
                                <i class="fas fa-file"></i>
                                Sertifikasi + TTD
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
