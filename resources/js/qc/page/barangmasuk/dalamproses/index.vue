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
                    text: 'Aksi',
                    value: 'aksi',
                },
            ],
        }
    },
    methods: {
        clickDetail(detail) {
            this.$router.push({
                name: 'barangMasukDetail',
                params: {
                    id: detail.id,
                    isRiwayat: false
                },
            })
        },
        refresh() {
            this.$emit('refresh')
        }
    },
}
</script>
<template>
    <div>
        <data-table :headers="headers" :items="data" :search="search">
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
                    <i class="fas fa-flask"></i>
                    Pengujian
                </button>
            </template>
        </data-table>
    </div>
</template>