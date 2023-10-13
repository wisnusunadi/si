<script>
import formPermintaan from './formPermintaan'
import status from '../../../components/status.vue';
export default {
    components: {
        formPermintaan,
        status,
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
        },
        refresh() {
            this.$emit('refresh');
        },
        statusReworks(belum, selesai) {
            if (selesai == 0) {
                return 'belum_dikerjakan'
            } else if (selesai > 0) {
                return 'sedang_dikerjakan'
            } else if (belum == 0) {
                return 'selesai'
            }
        }
    },
}
</script>
<template>
    <div>
        <formPermintaan v-if="showModal" :headerData="dataSelected" @closeModal="showModal = false" @refresh="refresh" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>PRD-{{ data.urutan }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td><status :status="statusReworks(data.belum, data.selesai)" /></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="detail(data)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>