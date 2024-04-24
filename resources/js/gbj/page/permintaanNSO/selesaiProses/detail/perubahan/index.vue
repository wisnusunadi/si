<script>
import tolak from './tolak.vue'
import pagination from '../../../../../../emiindo/components/pagination.vue'
export default {
    components: {
        tolak,
        pagination
    },
    props: ['perubahan'],
    data() {
        return {
            searchProduk: '',
            headersProduk: [
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
            renderPaginate: []
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
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filterRecursive() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.perubahan.filter(data => includesSearch(data, this.searchProduk));
        },
    }
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th v-for="header in headersProduk"
                                :class="header.align ? header.align : ''"
                                :key="header.value">{{ header.text }}</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <template v-for="(item, idx) in renderPaginate">
                                <tr>
                                    <td>
                                        <button class="btn btn-sm"
                                            :class="item.expanded ? 'btn-outline-danger' : 'btn-outline-primary'"
                                            @click="item.expanded = !item.expanded">
                                            <i class="fa" :class="item.expanded ? 'fa-minus' : 'fa-plus'"></i>
                                        </button>
                                    </td>
                                    <td>{{ item.no_ubah }}</td>
                                    <td>{{ item.nama }}</td>
                                    <td>{{ item.jumlah }}</td>
                                    <td>{{ ketPerubahan(item) }}</td>
                                    <td class="text-center">
                                        <div v-if="!item.hasil">
                                            <button class="btn btn-sm btn-outline-success" @click="terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" @click="tolakPengajuan(item)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            {{ item.hasil }}
                                        </div>
                                    </td>
                                    <td>{{ item.alasan ?? '-' }}</td>
                                </tr>
                                <tr v-if="item.expanded">
                                    <td colspan="100%">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor Seri</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="item.noseri.length > 0">
                                                <tr v-for="(noseri, idx2) in item.noseri">
                                                    <td>{{ idx2 + 1 }}</td>
                                                    <td>{{ noseri.noseri }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="100%" class="text-center">Tidak ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="100%" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filterRecursive"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />

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
