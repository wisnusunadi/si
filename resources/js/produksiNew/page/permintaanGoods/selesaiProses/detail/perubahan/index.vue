<script>
import formPerubahan from './formPerubahan.vue'
export default {
    components: {
        formPerubahan
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
                    text: 'No. Perubahan',
                    value: 'no_ubah'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Ket. Permintaan',
                    value: 'hari'
                },
                {
                    text: 'Hasil',
                    value: 'hasil'
                },
                {
                    text: 'Alasan Ditolak',
                    value: 'alasan'
                }
            ],
            items: [
                {
                    no: 1,
                    no_ubah: 'UBAH-2021080003',
                    nama: 'Produk 3',
                    jumlah: 3,
                    tanggal_selesai: '2024-04-03',
                    hari: 4,
                    hasil: null,
                    alasan: null,
                },
                {
                    no: 2,
                    no_ubah: 'UBAH-2021080003',
                    nama: 'Produk 1',
                    jumlah: 2,
                    tanggal_selesai: '2024-08-23',
                    hari: 1,
                    hasil: 'Diterima',
                    alasan: '-',
                },
                {
                    no: 3,
                    no_ubah: 'UBAH-2021080003',
                    nama: 'Produk 2',
                    jumlah: 3,
                    tanggal_selesai: '2024-04-03',
                    hari: 4,
                    hasil: 'Ditolak',
                    alasan: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc nec ultricies.',
                },
            ],
            showModal: false,
        }
    },
    methods: {
        addWeekDays(date, daysToAdd) {
            let dateCopy = new Date(date)
            let count = 0
            while (count < daysToAdd) {
                dateCopy.setDate(dateCopy.getDate() + 1)
                if (dateCopy.getDay() !== 0 && dateCopy.getDay() !== 6) {
                    count++
                }
            }
            return dateCopy
        },
        ketPerubahan(item) {
            let addTime = item.hari
            let dateAfter = this.addWeekDays(item.tanggal_selesai, addTime)
            return `Perpanjangan durasi peminjaman selama ${addTime} hari dengan tanggal pengembalian yang diubah dari ${this.dateFormat(item.tanggal_selesai)} menjadi ${this.dateFormat(dateAfter)}`
        },
        openModalPerubahan() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalPerubahan').modal('show');
            })
        }
    },
}
</script>
<template>
    <div class="card">
        <formPerubahan v-if="showModal" @close="showModal = false" />
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <button class="btn btn-sm btn-primary" @click="openModalPerubahan">
                        <i class="fa fa-plus"></i>
                        Permintaan Perubahan Durasi Peminjaman
                    </button>
                </div>
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                </div>
            </div>
            <data-table :headers="headers" :items="items" :search="search">
                <template #item.hari="{ item }">
                    <div>
                        {{ ketPerubahan(item) }}
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>