<script>
export default {
    props: ['data', 'search'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor BPPB',
                    value: 'bppb'
                },
                {
                    text: 'Produk',
                    value: 'produk',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Progress',
                    value: 'progress',
                },
                {
                    text: 'Tanggal Selesai Pengujian',
                    value: 'tanggal',
                    sortable: false,
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                },
            ],
            tanggalAwalPengujian: '',
            tanggalAkhirPengujian: '',
        }
    },
    methods: {
        clickDetail(detail) {
            this.$router.push({
                name: 'barangMasukDetail',
                params: {
                    id: detail.id,
                    isRiwayat: true
                }
            })
        },
        refresh() {
            this.$emit('refresh')
        },
        renderNo(data) {
            // foreach data
            return data.forEach((item, index) => {

            })
        },
    },
    computed: {
        filterRiwayat() {
            let filtered = this.data
            if (this.tanggalAwalPengujian && this.tanggalAkhirPengujian) {
                const startDate = new Date(this.tanggalAwalPengujian)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirPengujian)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai_uji)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalPengujian) {
                const startDate = new Date(this.tanggalAwalPengujian)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai_uji)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirPengujian) {
                const endDate = new Date(this.tanggalAkhirPengujian)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai_uji)
                    return date <= endDate
                })
            }

            return filtered

        }
    }
}
</script>
<template>
    <div>
        <data-table :headers="headers" :items="filterRiwayat" :search="search">
            <template #header.tanggal>
                <span class="text-bold pr-2">Tanggal Selesai Pengujian</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3 font-weight-normal">
                                <div class="form-group">
                                    <label for="">Tanggal Awal</label>
                                    <input type="date" class="form-control" v-model="tanggalAwalPengujian">
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Akhir</label>
                                    <input type="date" class="form-control" v-model="tanggalAkhirPengujian">
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #item.no="{ item, index }">
                {{ index + 1 }}
            </template>
            <template #item.jumlah="{ item }">
                <div>
                    {{ item.jumlah }} Unit
                    <br><span class="badge badge-dark">
                        Terisi: {{ item.terisi }} Unit
                    </span>
                </div>
            </template>
            <template #item.progress="{ item }">
                <div>
                    <!-- baru -->
                    <span class="badge badge-success">Lolos: {{ item.lolos }} Unit ({{ item.persentase_lolos }}%)</span>
                    <br>
                    <span class="badge badge-danger">Tidak Lolos: {{ item.tidak_lolos }} Unit ({{
                        item.persentase_tidak_lolos
                    }}%)</span>
                </div>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-info btn-sm" @click="clickDetail(item)">
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
    </div>
</template>