<script>
import status from '../../../../../components/status.vue';
export default {
    components: {
        status
    },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'No. Pengajuan',
                    value: 'no_pengajuan'
                },
                {
                    text: 'Versi Pengajuan',
                    value: 'versi_pengajuan'
                },
                {
                    text: 'Pengajuan',
                    value: 'pengajuan'
                },
                {
                    text: 'Tanggal Pengajuan',
                    value: 'tgl_pengajuan'
                },
                {
                    text: 'Hasil Pengajuan',
                    value: 'hasil'
                },
                {
                    text: 'Status Sebelum Pengajuan',
                    value: 'status_perubahan_pengajuan'
                },
                {
                    text: 'Alasan Ditolak Sebelum Pengajuan',
                    value: 'alasan'
                }
            ],
            items: [
                {
                    no: 1,
                    no_pengajuan: 'NSO-2021080001',
                    versi_pengajuan: 'V3',
                    pengajuan: [
                        'Perubahan Produk dari Tensione ke Digit One',
                        'Perubahan jumlah produk tensione dari 3 ke 5'
                    ],
                    tgl_pengajuan: '21 Agustus 2021',
                    status_perubahan_pengajuan: 'permintaan_ditolak_atasan',
                    hasil: 'Ditolak',
                    alasan: 'Lorem'
                },
                {
                    no: 2,
                    no_pengajuan: 'NSO-2021080002',
                    versi_pengajuan: 'V2',
                    pengajuan: [
                        'Perubahan Produk dari Tensione ke Digit One',
                        'Perubahan jumlah produk tensione dari 3 ke 5'
                    ],
                    tgl_pengajuan: '21 Agustus 2021',
                    status_perubahan_pengajuan: 'permintaan_gagal_diacc',
                    hasil: 'Diterima',
                    alasan: 'Lorem',
                }
            ],
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="headers" :items="items" :search="search">
                <template #item.pengajuan="{item}">
                    <ul>
                        <li v-for="pengajuan in item.pengajuan" :key="pengajuan">
                            {{ pengajuan }}
                        </li>
                    </ul>
                </template>
                <template #item.status_perubahan_pengajuan="{item}">
                    <div>
                        <status :status="item.status_perubahan_pengajuan" />
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>