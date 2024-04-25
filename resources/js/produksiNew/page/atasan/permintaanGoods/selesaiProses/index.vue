<script>
import status from '../../../../components/status.vue';
export default {
    components: { status },
    data() {
        return {
            search: '',
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
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [
                {
                    id: 1,
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Lorem',
                    tgl_pengambilan: '21 Agustus 2021',
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'selesai',
                },
                {
                    id: 2,
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Ipsum',
                    tgl_pengambilan: '-',
                    durasi: null,
                    jenis: 'permintaan',
                    status: 'batal'
                },
                {
                    id: 2,
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '21 Agustus 2021',
                    tujuan: 'Ipsum',
                    tgl_pengambilan: '21 Agustus 2021',
                    durasi: '1 Hari',
                    jenis: 'peminjaman',
                    status: 'selesai'
                }
            ]
        }
    },
    methods: {
        detail({ id, status, jenis }) {
            this.$router.push({ name: 'permintaanGoodsMgrDetail', params: { id, selesai: true, status, jenis } });
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.jenis="{ item }">
                <div class="text-capitalize">
                    {{ item.jenis }}
                </div>
            </template>
            <template #item.no_referensi="{ item }">
                <div>
                    {{ item.no_referensi }}
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>