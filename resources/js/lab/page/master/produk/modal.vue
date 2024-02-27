<script>
import axios from 'axios'
export default {
    props: ['produk'],
    data() {
        return {
            alat: [],
            produkNonLab: [],
            produkSelected: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalProduk').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getAlat() {
            try {
                const { data: alat } = await axios.get('/api/labs/kode').then(res => res.data)
                const { produkNonLab } = await axios.get('/api/produkLab').then(res => res.data)
                this.produkNonLab = produkNonLab.map(item => {
                    return {
                        label: item.nama,
                        value: item.id
                    }
                })
                this.alat = alat.map(item => {
                    return {
                        label: item.nama,
                        value: item.id
                    }
                })
                this.produkSelected = JSON.parse(JSON.stringify(this.produk))
            } catch (error) {
                console.log(error)
            }
        },
        tambahProduk() {
            this.produkSelected.push({
                nama: '',
                alat_selected: null
            })
        },
        async simpan() {
            const cekProdukNotNull = Object.keys(this.produkSelected).some(key => {
                return this.produkSelected[key].nama === '' || this.produkSelected[key].alat_selected === null
            })

            if (cekProdukNotNull) {
                swal.fire('Peringatan', 'Nama Produk dan Alat tidak boleh kosong', 'warning')
                return
            }

            const produkSelected = this.produkSelected.map(item => {
                return {
                    id: item.id ?? item.nama.value,
                    alat: item.alat_selected.value,
                }
            })

            try {
                const { data } = await axios.post('/api/labs/kode', produkSelected)
                this.closeModal()
                this.$emit('refresh')
                swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
            } catch (error) {
                console.log(error)
                swal.fire('Gagal', 'Data gagal disimpan', 'error')
            }
        },
        hapusProduk(item) {
            const index = this.produkSelected.findIndex(produk => produk === item)
            this.produkSelected.splice(index, 1)
        }
    },
    computed: {
        // jika produk sudah ada di produkSelected maka tidak akan ditampilkan
        produkNonLabFilter() {
            return this.produkNonLab.filter(item => {
                return !this.produkSelected.some(produk => produk?.nama.value === item.value)
            })
        }
    },
    created() {
        this.getAlat()
    }
}
</script>
<template>
    <div class="modal fade modalProduk" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Alat Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <button class="btn btn-primary" @click="tambahProduk">Tambah</button>
                        </div>
                        <div class="p-2 bd-highlight">
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Nama Produk</th>
                                <th style="width: 40%;">Alat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in produkSelected" :key="index">
                                <td>
                                    <v-select :options="produkNonLabFilter" v-model="item.nama" v-if="!item.id"></v-select>
                                    <span v-else>{{ item.nama }}</span>
                                </td>
                                <td>
                                    <v-select :options="alat" v-model="item.alat_selected"></v-select>
                                </td>
                                <td>
                                    <button class="btn btn-outline-danger" v-if="!item.id" @click="hapusProduk(item)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>