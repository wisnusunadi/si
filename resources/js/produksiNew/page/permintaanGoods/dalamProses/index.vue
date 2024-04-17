<script>
import status from '../../../components/status.vue';
export default {
    components: {
        status
    },
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Permintaan',
                    value: 'no_permintaan'
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_referensi'
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tgl_permintaan'
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan'
                },
                {
                    text: 'Tanggal Pengambilan',
                    value: 'tgl_pengambilan'
                },
                {
                    text: 'Durasi',
                    value: 'durasi'
                },
                {
                    text: 'Jenis',
                    value: 'jenis'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Persentase',
                    value: 'persentase'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            items: [
                {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Lorem',
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'persiapan_barang',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Ipsum',
                    tgl_pengambilan: null,
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'barang_siap_diambil',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Dolor',
                    tgl_pengambilan: null,
                    durasi: '1 Hari',
                    jenis: 'peminjaman',
                    status: 'barang_keluar',
                    persentase: 0,
                },
                {
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Sit Amet',
                    tgl_pengambilan: null,
                    durasi: '2 Hari',
                    jenis: 'permintaan',
                    status: 'barang_keluar',
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_pengambilan="{item}">
                <div v-if="item.tgl_pengambilan">

                </div>
            </template>
            <template #item.jenis="{item}">
                <div>
                    <status :status="item.jenis" />
                </div>
            </template>
            <template #item.status="{item}">
                <div>
                    <status :status="item.status" />
                </div>
            </template>
        </data-table>
    </div>
</template>