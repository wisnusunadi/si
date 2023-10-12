<script>
export default {
    props: ['dataTable', 'jumlah']
}
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>
                        <button class="btn btn-primary">
                            <i class="fas fa-qrcode"></i>
                            Nomor Seri
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>