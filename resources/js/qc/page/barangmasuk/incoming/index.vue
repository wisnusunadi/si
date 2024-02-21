<script>
import modalUji from './modalUji.vue'
export default {
    components: {
        modalUji
    },
    props: ['data'],
    data() {
        return {
            search: '',
            headers: [
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
            showModal: false,
            detail: {},
        }
    },
    methods: {
        clickDetail(detail) {
            this.detail = detail
            this.showModal = true
            this.$nextTick(() => {
                $('.modalUji').modal('show')
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
        <modalUji :produk="detail" v-if="showModal" @closeModal="showModal = false" @refresh="refresh" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="data" :search="search">
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
                    <span class="badge badge-success">Lolos: {{ item.lolos }} Unit {{ item.persentase_lolos }}%</span> <br>
                    <span class="badge badge-danger">Tidak Lolos: {{ item.tidak_lolos }} Unit {{ item.persentase_tidak_lolos
                    }}%</span>
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