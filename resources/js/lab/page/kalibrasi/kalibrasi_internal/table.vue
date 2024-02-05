<script>
import loading from '../../../components/loading.vue';
export default {
    components: {
        loading
    },
    props: ['dataTable'],
    methods: {

    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Nama Pemilik</th>
                    <th>Nama Pemilik Sertifikat</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.no_order }}</td>
                    <td>{{ data.pemilik }}</td>
                    <td>{{ data.pemilik_sertif }}</td>
                    <td>{{ data.customer }}</td>
                    <td>
                        <loading :persentase="data.status" />
                            <span class="text-muted">Selesai</span>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>