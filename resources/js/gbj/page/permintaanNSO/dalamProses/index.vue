<script>
import persentase from "../../../../emiindo/components/persentase.vue";
import status from "../../../components/status.vue";
export default {
    components: {
        persentase,
        status,
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
                    text: "Nama",
                    value: "nama",
                },
                {
                    text: "Bagian",
                    value: "bagian",
                },
                {
                    text: "Tujuan Permintaan",
                    value: "tujuan_permintaan",
                },
                {
                    text: "Tanggal Pengambilan",
                    value: "tgl_ambil",
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
                    tgl_ambil: "2024-08-24",
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan:
                        "perhitungan persentase: jika permintaan yang dihitung barang yang sudah dikeluarkan, jika peminjaman yang dihitung barang yang sudah dikembalikan",
                    durasi: "1 Hari",
                    durasi_tanggal: "2024-08-24",
                    status: "barangkeluar",
                    persentase: 50,
                    jenis: "Peminjaman", // berapa persen barang yang belum kembali
                },
                {
                    id: 2,
                    no_permintaan: "NSO-2021080001",
                    no_referensi: "SO-2021080001",
                    tgl_permintaan: "21 Agustus 2021",
                    tgl_ambil: "2024-08-24",
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan: "Lorem",
                    durasi: null,
                    durasi_tanggal: null,
                    status: "barangkeluar",
                    persentase: 50, // berapa persen barang yang dikeluarkan
                    jenis: "Permintaan",
                },
                {
                    id: 3,
                    no_permintaan: "NSO-2021080002",
                    no_referensi: "SO-2021080002",
                    tgl_permintaan: "22 Agustus 2021",
                    tgl_ambil: null,
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan: "Lorem",
                    durasi: "1 Hari",
                    durasi_tanggal: "2024-08-24",
                    status: "barangdisiapkan",
                    persentase: 0,
                    jenis: "Peminjaman",
                },
                {
                    id: 4,
                    no_permintaan: "NSO-2021080002",
                    no_referensi: "SO-2021080002",
                    tgl_permintaan: "22 Agustus 2021",
                    tgl_ambil: null,
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan: "Lorem",
                    durasi: null,
                    durasi_tanggal: null,
                    status: "barangdisiapkan",
                    persentase: 0,
                    jenis: "Permintaan",
                },
                {
                    id: 5,
                    no_permintaan: "NSO-2021080003",
                    no_referensi: "SO-2021080003",
                    tgl_permintaan: "23 Agustus 2021",
                    tgl_ambil: null,
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan: "Lorem",
                    durasi: null,
                    durasi_tanggal: null,
                    status: "barangsiapdiambil",
                    persentase: 0,
                    jenis: "Permintaan",
                },
                {
                    id: 6,
                    no_permintaan: "NSO-2021080003",
                    no_referensi: "SO-2021080003",
                    tgl_permintaan: "23 Agustus 2021",
                    tgl_ambil: null,
                    nama: "Bagus",
                    bagian: "Produksi",
                    tujuan_permintaan: "Lorem",
                    durasi: "1 Hari",
                    durasi_tanggal: "2024-08-24",
                    status: "barangsiapdiambil",
                    persentase: 0,
                    jenis: "Peminjaman",
                },
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
        aksi(item) {
            this.$router.push({
                name: "detailNSO",
                params: {
                    id: item.id,
                    selesai: false,
                    jenis: item.jenis,
                    status: item.status,
                },
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
            <template #item.status="{ item }">
                <div>
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.persentase="{ item }">
                <div>
                    <persentase :persentase="item.persentase"></persentase>
                </div>
            </template>
            <template #item.tgl_ambil="{ item }">
                <div>
                    {{ dateFormat(item.tgl_ambil) }}
                </div>
            </template>
            <template #item.durasi="{ item }">
                <div v-if="item.durasi">
                    <div
                        :class="calculateDateFromNow(item.durasi_tanggal).color"
                    >
                        {{ item.durasi }}
                    </div>
                    <small
                        :class="calculateDateFromNow(item.durasi_tanggal).color"
                    >
                        <i
                            :class="
                                calculateDateFromNow(item.durasi_tanggal).icon
                            "
                        ></i>
                        {{ calculateDateFromNow(item.durasi_tanggal).text }}
                    </small>
                </div>
                <div v-else>-</div>
            </template>
            <template #item.aksi="{ item }">
                <button
                    class="btn btn-sm btn-outline-primary"
                    @click="aksi(item)"
                >
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
    </div>
</template>
