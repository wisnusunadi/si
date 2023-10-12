<script>
export default {
    props: ['dataTable'],
}
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No. Seri</th>
                    <th>Tanggal Dibuat</th>
                    <th>Checker</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.noseri }}</td>
                    <td>{{ dateFormat(data.tanggal) }}</td>
                    <td>{{ data.checker }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detailNoseriProduk(data.noseri)">
                            <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-warning" @click="editNoseriProduk(data.noseri)">
                            <i class="fa fa-pencil"></i> Edit No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-danger" @click="detailNoseriProduk(data.noseri)">
                            <i class="fa fa-trash"></i> Hapus No. Seri Produk
                        </button>
                        <br>
                        <button class="btn btn-sm btn-outline-info my-1" @click="lihatNoseri(data.noseri)">
                            <i class="fa fa-eye"></i> Lihat No. Seri
                        </button>
                        <button class="btn btn-sm btn-outline-primary my-1" @click="cetakNoseri(data.noseri)">
                            <i class="fa fa-print"></i> Cetak No. Seri
                        </button> <br>
                        <button class="btn btn-sm btn-outline-info" @click="lihatNoseri(data.noseri)">
                            <i class="fa fa-eye"></i> Lihat Packing List
                        </button>
                        <button class="btn btn-sm btn-outline-primary" @click="cetakPackingList(data.noseri)">
                            <i class="fa fa-print"></i> Cetak Packing List
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>