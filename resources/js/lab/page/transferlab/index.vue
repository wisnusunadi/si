<script>
import pagination from "../../components/pagination.vue";
import Header from "../../components/header.vue";
import produk from "./produk.vue";
import loading from '../../components/loading.vue';
import axios from 'axios';
import riwayat from "./riwayatorder";
import riwayatseri from "./riwayatseri";
export default {
    components: {
        pagination,
        produk,
        Header,
        loading,
        riwayat,
        riwayatseri
    },
    data() {
        return {
            // header
            title: "Transfer Unit Kalibrasi",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Transfer Unit Kalibrasi",
                    link: "/lab/transfer",
                },
            ],
            search: "",
            searchEksternal: "",
            dataTable: [],
            dataTableEksternal: [],
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
            selectedSO: null,
            riwayatKalibrasi: [],
            showTabs: 'internal',
            transferKalibrasiNoSeri: [{ "no": "0001", "p_id": 6752, "no_po": "polabtestv1", "no_order": "LAB-0001", "tgl_masuk": "2024-02-21", "nama_alat": "ECG", "type": "ABPM50", "noseri": "PM622AB0074", "nama_pemilik": "PERUSAHAAN", "nama_pemilik_sert": "BIDDOKKES POLDA JATIM", "alamat": "- Kementerian PPN/Bappenas Jl. Taman Suropati No. 2 Menteng Jakarta Pusat Kota Jakarta Pusat DKI Jakarta", "tgl_kalibrasi": "2024-02-21", "no_sertifikat": "BPM/01/2-2024/0001", "pemeriksa": "Sri Wahyuni", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0001/LAB/02/24", "info": { "id": 6752, "ket": null, "tgl_buat": "2024-02-08", "tgl_kontrak": "2024-05-15", "no_urut": "1", "nama": "PT. GLOBAL DIGITAL NIAGA", "no_paket": "AK1-labtestv1", "instansi": "BIDDOKKES POLDA JATIM", "alamat_instansi": "- Kementerian PPN/Bappenas Jl. Taman Suropati No. 2 Menteng Jakarta Pusat Kota Jakarta Pusat DKI Jakarta", "satuan": "BADAN LAYANAN UMUM DAERAH RUMAH SAKIT UMUM KONAWE", "status": "sepakat" }, "tanggal": "21 Februari 2024" }, { "no": "0003", "p_id": 6758, "no_po": "polabtest", "no_order": "LAB-0003", "tgl_masuk": "2024-02-22", "nama_alat": "UV STERILIZER", "type": "CMS-600 PLUS", "noseri": "US03223A00004", "nama_pemilik": "RUMAH SAKIT", "nama_pemilik_sert": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "USG/02/2-2024/0003", "pemeriksa": "Siti Romlah", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0003/LAB/02/24", "info": { "id": 6758, "ket": null, "tgl_buat": "2024-02-22", "tgl_kontrak": "2024-02-22", "no_urut": "002", "nama": "PT. EMIINDO Jaya Bersama", "no_paket": "AK1-2626262", "instansi": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat_instansi": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "satuan": "Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas Batam", "status": "sepakat" }, "tanggal": "23 Februari 2024" }, { "no": "0004", "p_id": 6758, "no_po": "polabtest", "no_order": "LAB-0003", "tgl_masuk": "2024-02-22", "nama_alat": "UV STERILIZER", "type": "CMS-600 PLUS", "noseri": "US03225A00134", "nama_pemilik": "RUMAH SAKIT", "nama_pemilik_sert": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "USG/02/2-2024/0004", "pemeriksa": "Siti Romlah", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0003/LAB/02/24", "info": { "id": 6758, "ket": null, "tgl_buat": "2024-02-22", "tgl_kontrak": "2024-02-22", "no_urut": "002", "nama": "PT. EMIINDO Jaya Bersama", "no_paket": "AK1-2626262", "instansi": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat_instansi": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "satuan": "Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas Batam", "status": "sepakat" }, "tanggal": "23 Februari 2024" }, { "no": "0005", "p_id": 6758, "no_po": "polabtest", "no_order": "LAB-0003", "tgl_masuk": "2024-02-22", "nama_alat": "TIMBANGAN BAYI MEKANIK", "type": "CMS-600 PLUS", "noseri": "US03225A00148", "nama_pemilik": "RUMAH SAKIT", "nama_pemilik_sert": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "USG/03/2-2024/0005", "pemeriksa": "Sri Wahyuni", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0003/LAB/02/24", "info": { "id": 6758, "ket": null, "tgl_buat": "2024-02-22", "tgl_kontrak": "2024-02-22", "no_urut": "002", "nama": "PT. EMIINDO Jaya Bersama", "no_paket": "AK1-2626262", "instansi": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat_instansi": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "satuan": "Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas Batam", "status": "sepakat" }, "tanggal": "23 Februari 2024" }, { "no": "0006", "p_id": 6758, "no_po": "polabtest", "no_order": "LAB-0003", "tgl_masuk": "2024-02-22", "nama_alat": "USG", "type": "CMS-600 PLUS", "noseri": "US03225A00140", "nama_pemilik": "RUMAH SAKIT", "nama_pemilik_sert": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "USG/03/2-2024/0006", "pemeriksa": "Sri Wahyuni", "hasil": "Lolos Kalibrasi", "nosj": "0003/LAB/02/24", "info": { "id": 6758, "ket": null, "tgl_buat": "2024-02-22", "tgl_kontrak": "2024-02-22", "no_urut": "002", "nama": "PT. EMIINDO Jaya Bersama", "no_paket": "AK1-2626262", "instansi": "BALAI PENGAMANAN FASILITAS KESEHATAN SURABAYA", "alamat_instansi": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "satuan": "Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas Batam", "status": "sepakat" }, "tanggal": "23 Februari 2024" }, { "no": "0010", "p_id": 6759, "no_po": "po123", "no_order": "LAB-0004", "tgl_masuk": "2024-02-26", "nama_alat": "SPIROMETER", "type": "ABPM50", "noseri": "PM06242B00007", "nama_pemilik": "INSTANSI PENDIDIKAN", "nama_pemilik_sert": "Dinas Kesehatan", "alamat": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/01/2-2024/0010", "pemeriksa": "Adi Putra Firmantika", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0004/LAB/02/24", "info": { "id": 6759, "ket": null, "tgl_buat": "2024-02-02", "tgl_kontrak": null, "no_urut": "001", "nama": "PT. Glorya Medica Abadi", "no_paket": "AK1-123456", "instansi": "Dinas Kesehatan", "alamat_instansi": "-JL Raya Porong no.1 Sidoarjo, Kabupaten Sidoarjo, Jawa\r\nTimur", "satuan": "BADAN LAYANAN UMUM DAERAH RUMAH SAKIT UMUM KONAWE", "status": "negosiasi" }, "tanggal": "26 Februari 2024" }, { "no": "0012", "p_id": 6761, "no_po": "po531", "no_order": "LAB-0005", "tgl_masuk": "2024-02-26", "nama_alat": "UV STERILIZER", "type": "MTB-2MTR", "noseri": "TD08217A4271", "nama_pemilik": "KLINIK / PUSKESMAS", "nama_pemilik_sert": "Anggi Setiawan", "alamat": "Jl. Mastrip Kr. Pilang 12 Surabaya", "tgl_kalibrasi": "2024-02-24", "no_sertifikat": "TB/02/2-2024/0012", "pemeriksa": "Ahmad Siddiq", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0005/LAB/02/24", "info": { "id": 6761, "nama": "Anggi Setiawan", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "24 Februari 2024" }, { "no": "0013", "p_id": 6761, "no_po": "po531", "no_order": "LAB-0005", "tgl_masuk": "2024-02-26", "nama_alat": "UV STERILIZER", "type": "MTB-2MTR", "noseri": "TD08217A4215", "nama_pemilik": "KLINIK / PUSKESMAS", "nama_pemilik_sert": "Anggi Setiawan", "alamat": "Jl. Mastrip Kr. Pilang 12 Surabaya", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "TB/02/2-2024/0013", "pemeriksa": "Sri Wahyuni", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0005/LAB/02/24", "info": { "id": 6761, "nama": "Anggi Setiawan", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0014", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "ECG", "type": "ABPM50", "noseri": "PM06242B00005", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0014", "pemeriksa": "Sri Wahyuni", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0015", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "ECG", "type": "ABPM50", "noseri": "PM06242B00008", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0015", "pemeriksa": "Sri Wahyuni", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0016", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "ECG", "type": "ABPM50", "noseri": "PM06242B00010", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0016", "pemeriksa": "Mutmainah", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0017", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "FETAL DOPPLER", "type": "ABPM50", "noseri": "PM06242B00011", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "BPM/02/2-2024/0017", "pemeriksa": "Mutmainah", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "23 Februari 2024" }, { "no": "0018", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "FETAL DOPPLER", "type": "ABPM50", "noseri": "PM06242B00012", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-23", "no_sertifikat": "BPM/02/2-2024/0018", "pemeriksa": "Mutmainah", "hasil": "Tidak Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "23 Februari 2024" }, { "no": "0020", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "LAMPU PERIKSA", "type": "ABPM50", "noseri": "PM06242B00014", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0020", "pemeriksa": "Sri Wahyuni", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0021", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "LAMPU PERIKSA", "type": "ABPM50", "noseri": "PM06242B00015", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0021", "pemeriksa": "Sri Wahyuni", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0022", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "LAMPU PERIKSA", "type": "ABPM50", "noseri": "PM06242B00016", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0022", "pemeriksa": "Sri Wahyuni", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0027", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "UV STERILIZER", "type": "ABPM50", "noseri": "PM06242B00021", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0027", "pemeriksa": "Frida Chrisdianti", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }, { "no": "0028", "p_id": 6762, "no_po": "po123", "no_order": "LAB-0006", "tgl_masuk": "2024-02-26", "nama_alat": "UV STERILIZER", "type": "ABPM50", "noseri": "PM06242B00022", "nama_pemilik": "DINAS KESEHATAN", "nama_pemilik_sert": "ANDALAS SARANA MEDIKA, PT", "alamat": "Jl. Ikhlas Raya No. 22 Kubu Dalam Parak Karakah - Kec. Padang Timur Kota Padang 25126", "tgl_kalibrasi": "2024-02-26", "no_sertifikat": "BPM/02/2-2024/0028", "pemeriksa": "Frida Chrisdianti", "hasil": "Lolos Kalibrasi", "nosj": "0006/LAB/02/24", "info": { "id": 6762, "nama": "ANDALAS SARANA MEDIKA, PT", "ket": null, "no_paket": "", "instansi": "-", "alamat_instansi": "-", "status": "-", "satuan": "-", "no_urut": "-", "tgl_buat": "-", "tgl_kontrak": "-" }, "tanggal": "26 Februari 2024" }],
        };
    },
    methods: {
        transfer(data) {
            this.selectedSO = data;
            this.modal = true;
            this.$nextTick(() => {
                $(".modalProduk").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/tf").then((res) => res.data);
                const { data: riwayat_kalibrasi } = await axios.get(`/api/labs/tf_riwayat?years=${this.$store.state.years}`);
                this.dataTable = data
                this.dataTableEksternal = data
                this.riwayatKalibrasi = riwayat_kalibrasi.map(item => {
                    return {
                        ...item,
                        tgl_transfer: this.formatDate(item.tgl_transfer),
                        detail: item.detail.map((produk, index) => {
                            return {
                                ...produk,
                                no: index + 1,
                                noseri: produk.noseri.map((noseri, index) => {
                                    return {
                                        ...noseri,
                                        no: index + 1,
                                    }
                                })
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        changeYear() {
            this.getData();
        },
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button"
                    @click="showTabs = 'internal'" role="tab" aria-controls="pills-home" aria-selected="true">Kalibrasi</a>

            </li>
            <!-- <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button"
                    @click="showTabs = 'eksternal'" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Eksternal</a>
            </li> -->
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button"
                    @click="showTabs = 'riwayat'" role="tab" aria-controls="pills-contact" aria-selected="false">Riwayat</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                v-if="showTabs == 'internal'">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-dalamproses-tab" data-toggle="pill"
                                    data-target="#pills-dalamproses" type="button" role="tab"
                                    aria-controls="pills-dalamproses" aria-selected="true">Dalam Proses</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                                    type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Selesai
                                    Proses</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-dalamproses" role="tabpanel"
                                aria-labelledby="pills-dalamproses-tab">
                                <produk v-if="modal" @close="modal = false" :headerSO="selectedSO" @refresh="getData" />
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..."
                                                v-model="search" />
                                        </div>
                                    </div>
                                </div>

                                <data-table :headers="headers" :items="dataTable" :search="search"
                                    v-if="!$store.state.loading">
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-outline-primary btn-sm" @click="transfer(item)">
                                            Transfer
                                        </button>
                                    </template>
                                </data-table>
                                <div class="spinner-border spinner-border-sm" role="status" v-else>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-riwayat" role="tabpanel"
                                aria-labelledby="pills-riwayat-tab">
                                <riwayat :dataRiwayat="riwayatKalibrasi" @changeYears="changeYear" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                v-if="showTabs == 'eksternal'">
                <div class="card">
                    <div class="card-body">
                        <produk v-if="modal" @close="modal = false" :headerSO="selectedSO" @refresh="getData" />
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..."
                                        v-model="searchEksternal" />
                                </div>
                            </div>
                        </div>

                        <data-table :headers="headers" :items="dataTableEksternal" :search="searchEksternal"
                            v-if="!$store.state.loading">
                            <template #item.aksi="{ item }">
                                <button class="btn btn-outline-primary btn-sm" @click="transfer(item)">
                                    Transfer
                                </button>
                            </template>
                        </data-table>
                        <div class="spinner-border spinner-border-sm" role="status" v-else>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                v-if="showTabs == 'riwayat'">
                <div class="card">
                    <div class="card-body">
                        <riwayatseri :noseri="transferKalibrasiNoSeri" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
