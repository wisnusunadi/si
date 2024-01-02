<script>
import axios from 'axios';
import DataTable from '../../../components/DataTable.vue';
import tambah from './tambah.vue';
export default {
    components: {
        DataTable,
        tambah
    },
    data() {
        return {
            headers: [
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Tanggal Rakit',
                    value: 'tgl_rakit'
                },
                {
                    text: 'Nama Produk',
                    value: 'produk'
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jml'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [
                {
                    no_bppb: 'BPPB/2020/01/001',
                    tgl_rakit: '23 September 2020',
                    produk: 'Produk 1',
                    jml: '10',
                },
                {
                    no_bppb: 'BPPB/2020/01/002',
                    tgl_rakit: '23 September 2020',
                    produk: 'Produk 2',
                    jml: '10',
                },
                {
                    no_bppb: 'BPPB/2020/01/003',
                    tgl_rakit: '23 September 2020',
                    produk: 'Produk 3',
                    jml: '10',
                }
            ],
            search: '',
            modalTambah: false,
        }
    },
    methods: {
        openModalTambah() {
            this.modalTambah = true
            this.$nextTick(() => {
                $('.modalFleksibel').modal('show')
            })
        },

    },

}
</script>
<template>
    <div>
        <tambah v-if="modalTambah" @closeModal="modalTambah = false" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="openModalTambah">
                    <i class="fa fa-plus"></i>
                    Tambah
                </button>
            </div>
            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="items" :search="search">
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-secondary btn-sm" @click="openDetailRakit(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </DataTable>
    </div>
</template>