<script>
export default {
    props: ['perubahan'],
    data() {
        return {
            searchProduk: '',
            headersProduk: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
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
                    text: 'Diterima',
                    value: 'diterima'
                },
                {
                    text: 'Alasan Ditolak',
                    value: 'alasan'
                },
            ],
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
        }
    },
}
</script>
<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="perubahan" :search="searchProduk">
                        <template #item.hari="{ item }">
                            <div>
                                {{ ketPerubahan(item) }}
                            </div>
                        </template>
                        <template #item.waktu_kembali="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_kembali) }}
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>