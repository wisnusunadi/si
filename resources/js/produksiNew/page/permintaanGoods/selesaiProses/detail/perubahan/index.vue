<script>
import pagination from '../../../../../../emiindo/components/pagination.vue'
import formPerubahan from './formPerubahan.vue'
export default {
    components: {
        formPerubahan,
        pagination
    },
    data() {
        return {
            search: '',
            headers: [
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
                    expanded: false,
                    noseri: [
                        {
                            noseri: '123456',
                            id: 1
                        },
                        {
                            noseri: '123457',
                            id: 2
                        },
                        {
                            noseri: '123458',
                            id: 3
                        }
                    ]
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
                    expanded: false,
                    noseri: [
                        {
                            noseri: '123456',
                            id: 1
                        },
                        {
                            noseri: '123457',
                            id: 2
                        }
                    ]
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
                    expanded: false,
                    noseri: [
                        {
                            noseri: '123456',
                            id: 1
                        },
                        {
                            noseri: '123457',
                            id: 2
                        },
                        {
                            noseri: '123458',
                            id: 3
                        }
                    ]
                },
            ],
            renderPaginate: [],
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

            return this.items.filter(data => includesSearch(data, this.search));
        },
    },
    created() {
        if (this.$route.params.selesai) {
            this.items = [
                {
                    no: 2,
                    no_ubah: 'UBAH-2021080003',
                    nama: 'Produk 1',
                    jumlah: 2,
                    tanggal_selesai: '2024-08-23',
                    hari: 1,
                    hasil: 'Diterima',
                    alasan: '-',
                    expanded: false,
                    noseri: [
                        {
                            noseri: '123456',
                            id: 1
                        },
                        {
                            noseri: '123457',
                            id: 2
                        }
                    ]
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
                    expanded: false,
                    noseri: [
                        {
                            noseri: '123456',
                            id: 1
                        },
                        {
                            noseri: '123457',
                            id: 2
                        },
                        {
                            noseri: '123458',
                            id: 3
                        }
                    ]
                },
            ]
        }
    }
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
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th v-for="header in headers" :key="header.value">{{ header.text }}</th>
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
                            <td>{{ item.hasil }}</td>
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
            <pagination :filteredDalamProses="filterRecursive" @updateFilteredDalamProses="updateFilteredDalamProses" />
        </div>
    </div>
</template>