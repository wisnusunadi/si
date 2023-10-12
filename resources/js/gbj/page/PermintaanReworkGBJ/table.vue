<script>
import formPermintaan from './formPermintaan'
export default {
    components: {
        formPermintaan,
    },
    props: ['dataTable'],
    data() {
        return {
            dataSelected: {},
            showModal: false,
        }
    },
    methods: {
        detail(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data));
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPermintaanRework').modal('show');
            });
        }
    },
}
</script>
<template>
    <div>
        <formPermintaan v-if="showModal" :headerData="dataSelected" @closeModal="showModal = false" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>PRD-{{ data.urutan }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="detail(data)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>