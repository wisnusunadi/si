<script>
export default {
    props: ['dataTable']
}
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ dateFormat(data.tanggal_mulai) }}</td>
                    <td>{{ dateFormat(data.tanggal_selesai) }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>