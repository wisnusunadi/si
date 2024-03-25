<script>
import pagination from '../../../components/pagination.vue';
export default {
    components: {
        pagination
    },
    data() {
        return {
            search: '',
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">No SO</th>
                    <th rowspan="2">No PO</th>
                    <th colspan="2">Pengiriman</th>
                    <th rowspan="2">Customer</th>
                    <th rowspan="2">Keterangan</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Awal</th>
                    <th>Akhir</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</template>