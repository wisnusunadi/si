<script>
import tolak from './tolak.vue'
export default {
    components: {
        tolak
    },
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
                    text: 'No. Perubahan',
                    value: 'no_ubah'
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
                    text: 'Hasil',
                    value: 'hasil',
                    align: 'customWidthResult text-center'
                },
                {
                    text: 'Alasan Ditolak',
                    value: 'alasan'
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
        terima() {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah diterima tidak dapat diubah lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        'Diterima!',
                        'Data berhasil diterima',
                        'success'
                    )
                }
            })
        },
        tolakPengajuan(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalAlasan').modal('show')
            })
        }
    },
}
</script>
<template>
    <div class="row">
        <tolak v-if="showModal" :detail="detailSelected" @close="showModal = false" />
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="perubahan" :search="searchProduk">
                        <template #item.hasil="{ item }">
                            <div v-if="!item.hasil">
                                <button class="btn btn-sm btn-outline-success" @click="terima">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" @click="tolakPengajuan(item)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
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
<style>
.customWidthResult {
    width: 10%;
}
</style>
