<script>
import modalDetail from '../modalDetail'
import LihatSeri from '../modalCreate/modalSeri.vue'
import axios from 'axios'
export default {
    components: {
        modalDetail,
        LihatSeri
    },
    data() {
        return {
            detailSeri: false,
            dataModalDetail: null,
            showModalDetail: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false
        }
    },
    props: ['dataTable'],
    methods: {
        detailNoseriProduk(id) {
            this.detailSeri = true;
            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show');
            });
        },
        detailProdukSeri(data) {
            this.dataModalDetail = JSON.parse(JSON.stringify(data))
            this.showModalDetail = true

            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show')
            })
        },
                lihatNoseri(noseri) {
            this.dataLihatNoSeri = noseri
            this.showModalNoSeri = true
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        hapusNoseriProduk(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus data no. seri produk ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/prd/rw/gen/${id}`).then(() => {
                        this.$swal({
                            title: 'Berhasil!',
                            text: 'Data berhasil dihapus',
                            icon: 'success',
                        })
                        this.$emit('refresh')
                    }).catch((err) => {
                        this.$swal({
                            title: 'Gagal!',
                            text: 'Data gagal dihapus',
                            icon: 'error',
                        })
                    })
                }
            })
        },
        editNoseriProduk(data) {
            this.$emit('editNoseriProduk', data)
        },
        lihatPackingList(id) {
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpackinglist/${id}`, '_blank');
        },
    },
}
</script>
<template>
    <div>
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal = "showModalNoSeri = false" />
        <modalDetail v-if="showModalDetail" @closeModal="showModalDetail = false" :dataModalDetailSeri="dataModalDetail" />
        <table class="table">
            <thead>
                <tr>
                    <th>No. Seri</th>
                    <th>Tanggal Dibuat</th>
                    <th>Packer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.noseri }}</td>
                    <td>{{ dateFormat(data.tgl_buat) }}</td>
                    <td>{{ data.packer }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(data)">
                            <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-warning" @click="editNoseriProduk(data)" v-if="data.status != 'Transfer'">
                            <i class="fa fa-pencil"></i> Edit No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-danger" @click="hapusNoseriProduk(data.id)" v-if="data.status != 'Transfer'">
                            <i class="fa fa-trash"></i> Hapus No. Seri Produk
                        </button>
                        <br>
                        <button class="btn btn-sm btn-outline-info my-1" @click="lihatNoseri(data.noseri)">
                            <i class="fa fa-eye"></i> Lihat No. Seri
                        </button>
                        <button class="btn btn-sm btn-outline-primary my-1" @click="cetakNoseri(data.id)">
                            <i class="fa fa-print"></i> Cetak No. Seri
                        </button> <br>
                        <button class="btn btn-sm btn-outline-info" @click="lihatPackingList(data.id)">
                            <i class="fa fa-eye"></i> Lihat Packing List
                        </button>
                        <button class="btn btn-sm btn-outline-primary" @click="cetakPackingList(data.id)">
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