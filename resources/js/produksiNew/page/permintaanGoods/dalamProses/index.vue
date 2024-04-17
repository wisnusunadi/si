<script>
export default {
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
                    tgl_pengambilan: '22 Agustus 2021',
                    durasi: '1 hari',
                    jenis: 'peminjaman',
                    status: 'Dalam Proses',
                    persentase: 50,
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
        <data-table :headers="headers" :items="items" :search="search" />
    </div>
</template>