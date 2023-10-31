<script>
import modalDetail from '../modalDetail'
import LihatSeri from '../modalCreate/modalSeri.vue'
import axios from 'axios'
import DataTable from '../../../../components/DataTable.vue'
export default {
    components: {
        modalDetail,
        LihatSeri,
    },
    data() {
        return {
            detailSeri: false,
            dataModalDetail: null,
            showModalDetail: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false,
        }
    },
    props: ['dataTable', 'search'],
    methods: {

        checkAllSeri() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.filterData.map((item) => item.id);
            } else {
                this.noSeriSelected = [];
            }
        },
        selectNoSeri(noseri) {
            if(this.noSeriSelected.find((data) => data === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data !== noseri)
                this.checkAll = false
            } else {
                this.noSeriSelected.push(noseri)
            }

            if(this.noSeriSelected.length === this.dataTable.length) {
                this.checkAll = true
            }
        }
    },
    computed: {
        filterData() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    },
    watch: {
        search() {
            if (this.noSeriSelected.length == this.dataTable.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    }
}
</script>
<template>
    <div>
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal="showModalNoSeri = false" />
        <modalDetail v-if="showModalDetail" @closeModal="showModalDetail = false" :dataModalDetailSeri="dataModalDetail" />

        <!-- <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>No. Seri</th>
                    <th>Tanggal Dibuat</th>
                    <th>Packer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td><input type="checkbox" name="" id=""></td>
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
                        <button class="btn btn-sm btn-outline-primary my-1" @click="cetakNoseri(data.noseri)">
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
        </table> -->
    </div>
</template>