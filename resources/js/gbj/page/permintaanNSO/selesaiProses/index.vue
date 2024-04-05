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
                    text: 'Nama',
                    value: 'nama'
                },
                {
                    text: 'Bagian',
                    value: 'bagian'
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan_permintaan'
                },
                {
                    text: 'Tanggal Pengambilan',
                    value: 'tgl_ambil'
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
                    text: 'Keterangan',
                    value: 'ket'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            items: [
                {
                    id: 1,
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_ambil: '2024-08-24',
                    nama: 'Bagus',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: '1 Hari',
                    jenis: 'Peminjaman',
                    status: 'tolak',
                    ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
                {
                    id: 2,
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_ambil: '2024-08-24',
                    nama: 'Bagus',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: '1 Hari',
                    jenis: 'Peminjaman',
                    status: 'selesai',
                    ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
                {
                    id: 3,
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_ambil: '2024-08-24',
                    nama: 'Bagus',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: '1 Hari',
                    jenis: 'Peminjaman',
                    status: 'selesai',
                    ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
                {
                    id: 4,
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_ambil: '2024-08-24',
                    nama: 'Bagus',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: null,
                    jenis: 'Permintaan',
                    status: 'selesai',
                    ket: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
            ]
        }
    },
    methods: {
        detail(item) {
            this.$router.push({
                name: 'detailNSO',
                params: {
                    id: item.id,
                    selesai: true,
                    jenis: item.jenis
                }
            })
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
                <template #item.no_referensi="{ item }">
                    {{ item.no_referensi }}
                    <status :status="item.status" />
                </template>
                <template #item.ket="{ item }">
                    <!-- truncate text -->
                    <div class="text-truncate" style="max-width: 150px;">
                        {{ item.ket }}
                    </div>
                </template>
                <template #item.aksi="{ item }">
                    <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </template>
            </data-table>
        </div>
    </div>
</template>
