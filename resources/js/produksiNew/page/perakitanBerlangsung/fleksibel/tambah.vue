<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
import modalPilihan from '../perakitan/modalPilihan.vue'
export default {
    components: {
        DataTable,
        modalPilihan
    },
    props: ['detailRakit'],
    data() {
        return {
            form: {
                no_bppb: '',
                produk: '',
                jml: '',
                bagian: '',
                tujuan: '',
            },
            produk: [],
            loading: false,
            hasilGenerate: [],
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                    align: 'text-left',
                }
            ],
            search: '',
            showModalCetak: false,
            bagian: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalFleksibel').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        keyUpperCase(e) {
            this.form.no_bppb = e.target.value.toUpperCase()
        },
        async getData() {
            try {
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                const { data: bagian } = await axios.get('/api/gbj/sel-divisi', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('lokal_token')
                    }
                })
                this.produk = produk.reduce((acc, item) => {
                    if(item.gudang_barang_jadi.length > 0) {
                        item.gudang_barang_jadi.forEach(variasi => {
                            acc.push({
                                label: `${item.nama} ${variasi.nama}`,
                                value: variasi.id
                            })
                        })
                    }
                    return acc
                }, [])

                this.bagian = bagian.map(item => {
                    return {
                        label: item.nama,
                        value: item.id
                    }
                })
            } catch (error) {
                console.log(error);
            }
        },
        async simpan() {
            // validasi
            const cekForm = Object.values(this.form).filter(item => item === '')
            if (cekForm.length > 0) {
                this.$swal('Perhatian', 'Form tidak boleh kosong', 'warning')
                return
            }

            // simpan
            try {
                this.loading = true
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                this.hasilGenerate = [
                    {
                        noseri: '1234567890',
                    },
                    {
                        noseri: '1234567890',
                    },
                    {
                        noseri: '1234567890',
                    }
                ]
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false
            }
        },
        cetakSeri() {
            this.showModalCetak = true
            this.$nextTick(() => {
                $('.modalFleksibel').modal('hide')
                $('.modalPilihan').modal('show')
            })
        },
        closeModalCetak() {
            this.showModalCetak = false
            this.$nextTick(() => {
                $('.modalFleksibel').modal('show')
            })
        },
    },
    created() {
        this.getData()
    },
}
</script>
<template>
    <div>
        <modalPilihan v-if="showModalCetak" @closeModal="closeModalCetak" :data="hasilGenerate"></modalPilihan>
        <div class="modal fade modalFleksibel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Perakitan Tanpa Jadwal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">No BPPB</label>
                                    <input type="text" v-model="form.no_bppb" class="form-control" @keyup="keyUpperCase"
                                        :disabled="hasilGenerate.length > 0">
                                </div>

                                <div class="form-group">
                                    <label for="">Nama Produk</label>
                                    <v-select :options="produk" v-model="form.produk" placeholder="Pilih Produk"
                                        :disabled="hasilGenerate.length > 0"></v-select>
                                </div>

                                <div class="form-group">
                                    <label for="">Jumlah Rakit</label>
                                    <input type="text" class="form-control" v-model="form.jml"
                                        @keypress="numberOnly($event)" :disabled="hasilGenerate.length > 0">
                                </div>

                                <div class="form-group">
                                    <label for="">Bagian (Peminta No Seri)</label>
                                    <v-select :options="bagian" v-model="form.bagian" placeholder="Bagian" :disabled="hasilGenerate.length > 0"></v-select>
                                </div>

                                <div class="form-group">
                                    <label for="">Tujuan (Minta No Seri)</label>
                                    <textarea class="form-control" v-model="form.tujuan" rows="3" :disabled="hasilGenerate.length > 0"></textarea>

                                </div>
                            </div>
                            <div class="col" v-if="hasilGenerate.length > 0">
                                <p class="text-bold">Hasil Generate No. Seri</p>
                                <DataTable :headers="headers" :items="hasilGenerate" :search="search"></DataTable>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <div v-if="hasilGenerate.length == 0">
                                    <button class="btn btn-success" :disabled="loading" @click="simpan">Generate</button>
                                </div>

                                <button class="btn btn-success" @click="cetakSeri" v-else>Cetak Barcode</button>
                            </div>

                            <div class="p-2 bd-highlight">
                                <button class="btn btn-secondary" @click="closeModal">Keluar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>