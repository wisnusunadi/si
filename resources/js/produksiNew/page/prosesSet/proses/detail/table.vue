<script>
import modalDetailSeri from '../modalDetail'
import LihatSeri from '../modalCreate/modalSeri.vue'
export default {
    components: {
        modalDetailSeri,
        LihatSeri
    },
    data() {
        return {
            detailSeri: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false,
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
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil dihapus',
                        icon: 'success',
                    })
                }
            })
        },
        editNoseriProduk(data) {
            this.$emit('editNoseriProduk', data)
        },
    },
}
</script>
<template>
    <div>
        <modalDetailSeri v-if="detailSeri" @closeModal="detailSeri = false" />
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal = "showModalNoSeri = false" />
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
                        <button class="btn btn-sm btn-outline-info" @click="detailNoseriProduk(data.id)">
                            <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-warning" @click="editNoseriProduk(data)">
                            <i class="fa fa-pencil"></i> Edit No. Seri Produk
                        </button>
                        <button class="btn btn-sm btn-outline-danger" @click="hapusNoseriProduk(data.id)">
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