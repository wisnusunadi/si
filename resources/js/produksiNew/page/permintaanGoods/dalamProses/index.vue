<script>
import status from "../../../components/status.vue";
import persentase from "../../../../emiindo/components/persentase.vue";
export default {
    components: {
        status,
        persentase,
    },
    data() {
        return {
            headers: [
                {
                    text: "Nomor Permintaan",
                    value: "no_permintaan",
                },
                {
                    text: "Nomor Referensi",
                    value: "no_referensi",
                },
                {
                    text: "Tanggal Permintaan",
                    value: "tgl_permintaan",
                },
                {
                    text: "Tujuan Permintaan",
                    value: "tujuan",
                    align: "text-center text-truncate maxWidthTujuan"
                },
                {
                    text: "Tanggal Pengambilan",
                    value: "tgl_pengambilan",
                },
                {
                    text: "Durasi",
                    value: "durasi",
                },
                {
                    text: "Jenis",
                    value: "jenis",
                },
                {
                    text: "Status",
                    value: "status",
                },
                {
                    text: "Persentase",
                    value: "persentase",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            search: "",
            items: [
                {
                    id: 1,
                    no_permintaan: "NSO-2021080001",
                    no_referensi: "SO-2021080001",
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "perhitungan persentase: jika permintaan (barang yang sudah diterima), jika peminjaman (barang yang sudah dikembalikan)",
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: "permintaan",
                    status: "persiapan_barang",
                    persentase: 0,
                },
                {
                    id: 2,
                    no_permintaan: "NSO-2021080002",
                    no_referensi: "SO-2021080002",
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "Ipsum",
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: "permintaan",
                    status: "barang_siap_diambil",
                    persentase: 0,
                },
                {
                    id: 4,
                    no_permintaan: "NSO-2021080004",
                    no_referensi: "SO-2021080004",
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "Sit Amet",
                    tgl_pengambilan: "2024-08-22",
                    durasi: null,
                    jenis: "permintaan",
                    status: "barang_keluar",
                    persentase: 0,
                },
                {
                    id: 5,
                    no_permintaan: "NSO-2021080005",
                    no_referensi: null,
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "Consectetur",
                    tgl_pengambilan: "2024-08-22",
                    durasi: "3 Hari",
                    jenis: "peminjaman",
                    status: "proses_peminjaman",
                    persentase: 50,
                },
                {
                    id: 5,
                    no_permintaan: "NSO-2021080005",
                    no_referensi: null,
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "Consectetur",
                    tgl_pengambilan: "2024-08-22",
                    durasi: "3 Hari",
                    jenis: "peminjaman",
                    status: "pengembalian",
                    persentase: 100,
                },
                {
                    id: 6,
                    no_permintaan: "NSO-2021080006",
                    no_referensi: "SO-2021080006",
                    tgl_permintaan: "21 Agustus 2021",
                    tujuan: "Adipisicing",
                    tgl_pengambilan: "2024-08-22",
                    durasi: "3 Hari",
                    jenis: "peminjaman",
                    status: "menunggu_penerimaan_barang",
                    persentase: 0,
                }
            ],
        };
    },
    methods: {
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
                    text: "Batas Peminjaman Habis",
                    color: "text-danger",
                    icon: "fas fa-exclamation-circle",
                };
            }
        },
        detail({ id, jenis, status }) {
            this.$router.push({
                name: "permintaanGoodsDetail",
                params: { id, selesai: false, jenis, status },
            });
        },
    },
};
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input
                    type="text"
                    class="form-control"
                    v-model="search"
                    placeholder="Cari..."
                />
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_pengambilan="{ item }">
                <div v-if="item.tgl_pengambilan"></div>
            </template>
            <template #item.jenis="{ item }">
                <div class="text-capitalize">
                    {{ item.jenis }}
                </div>
            </template>
            <template #item.status="{ item }">
                <div>
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.persentase="{ item }">
                <div>
                    <persentase :persentase="item.persentase" />
                </div>
            </template>
            <template #item.tgl_pengambilan="{ item }">
                {{ dateFormat(item.tgl_pengambilan) }}
            </template>
            <template #item.durasi="{ item }">
                <div v-if="item.durasi">
                    <div
                        :class="
                            calculateDateFromNow(item.tgl_pengambilan).color
                        "
                    >
                        {{ item.durasi }}
                    </div>
                    <small
                        :class="
                            calculateDateFromNow(item.tgl_pengambilan).color
                        "
                    >
                        <i
                            :class="
                                calculateDateFromNow(item.tgl_pengambilan).icon
                            "
                        ></i>
                        {{ calculateDateFromNow(item.tgl_pengambilan).text }}
                    </small>
                </div>
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <button
                        class="btn btn-outline-primary btn-sm"
                        @click="detail(item)"
                    >
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>
<style>
.maxWidthTujuan {
    max-width: 200px;
}
</style>