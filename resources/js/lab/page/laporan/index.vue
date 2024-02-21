<script>
import axios from 'axios';
import Header from '../../components/header.vue';
export default {
    components: {
        Header
    },
    data() {
        return {
            title: 'Laporan',
            breadcumbs: [
                { name: 'Home', link: '/' },
                { name: 'Laporan', link: '/laporan' }
            ],
            customer: [],
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Tgl Masuk',
                    value: 'tgl_masuk'
                },
                {
                    text: 'Nama Alat',
                    value: 'nama_alat'
                },
                {
                    text: 'Tipe',
                    value: 'type'
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'nama_pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'nama_pemilik_sert'
                },
                {
                    text: 'Alamat',
                    value: 'alamat'
                },
                {
                    text: 'Tgl Kalibrasi',
                    value: 'tgl_kalibrasi'
                },
                {
                    text: 'Teknisi',
                    value: 'pemeriksa'
                },
                {
                    text: 'No Sertifikat',
                    value: 'no_sertifikat',
                },
                {
                    text: 'No SJ',
                    value: 'nosj'
                },
                {
                    text: 'Nama Distributor',
                    value: 'nama_distributor'
                },
                {
                    text: 'No PO',
                    value: 'no_po'
                },
                {
                    text: 'Status',
                    value: 'hasil'
                }
            ],
            form: {
                customer: {
                    label: 'Semua Distributor',
                    value: null
                },
                jenis: [],
                tanggal_awal: null,
                tanggal_akhir: null
            },
            dateNow: new Date().toISOString().substr(0, 10),
            hasil: [],
            search: ''
        }
    },
    methods: {
        async getData() {
            try {
                const { data: customer } = await axios.get('/api/customer/select/')
                this.customer = customer.map(c => ({ label: c.nama, value: c.id }))
                this.customer.unshift({ label: 'Semua Distributor', value: null })
            } catch (error) {
                console.error(error)
            }
        },
        resetForm() {
            this.form = {
                customer: {
                    label: 'Semua Distributor',
                    value: null
                },
                tanggal_awal: null,
                tanggal_akhir: null
            }
            this.hasil = []
        },
        async cetak() {
            const cekFormNotEmpty = Object.keys(this.form).every(key => {
                if (Array.isArray(this.form[key])) {
                    return this.form[key].length > 0
                }

                return this.form[key]
            })

            if (cekFormNotEmpty) {
                // axios.get(`/api/labs/riwayat_uji`).then(({ data }) => {
                //     this.hasil = data.map(d => ({
                //         nama_distributor: d.info.nama,
                //         ...d
                //     }))
                // })
                try {
                    const { data } = await axios.post('/api/labs/riwayat_uji_laporan', this.form)
                    this.hasil = data.map(d => ({
                        nama_distributor: d.info.nama,
                        ...d
                    }))
                } catch (error) {

                }
            } else {
                swal.fire('Peringatan', 'Form pencarian tidak boleh kosong', 'warning')
                return
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-header bg-gray text-white">
                <h3 class="card-title">Pencarian</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-5 col-md-12 text-right">Distributor / Customer</label>
                    <div class="col-4">
                        <v-select :options="customer" v-model="form.customer" />
                        <small>Distributor / Customer boleh dikosongi</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="penjualan" class="col-form-label col-lg-5 col-md-12 text-right">Jenis</label>
                    <div class="col-5 col-form-label">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="penjualan" value="internal" name="penjualan"
                                v-model="form.jenis">
                            <label class="form-check-label" for="inlineCheckbox1">Internal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="penjualan" value="eksternal"
                                name="penjualan" v-model="form.jenis">
                            <label class="form-check-label" for="inlineCheckbox1">Eksternal</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-5 col-md-12 text-right">Tanggal Awal Kalibrasi</label>
                    <div class="col-3">
                        <input type="date" class="form-control" v-model="form.tanggal_awal" :max="dateNow" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-lg-5 col-md-12 text-right">Tanggal Akhir Kalibrasi</label>
                    <div class="col-3">
                        <input type="date" class="form-control" v-model="form.tanggal_akhir" :max="dateNow"
                            :disabled="!form.tanggal_awal" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-5"></div>
                    <div class="col-lg-4 col-md-12">
                        <span class="float-right filter"><button type="submit" class="btn btn-success"
                                @click="cetak">Cetak</button></span>
                        <span class="float-right filter mr-1"><button type="button" class="btn btn-outline-danger"
                                @click="resetForm" id="btnbatal">Batal</button></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" v-if="hasil.length > 0">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i>
                            Export
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                    </div>
                </div>
                <data-table :headers="headers" :items="hasil" :search="search" />
            </div>
        </div>
    </div>
</template>