<script>
import Table from "./table.vue";
import axios from "axios";
export default {
    props: ["productSelected", "header"],
    data() {
        return {
            karyawan: [],
            form: {
                uji_lab_id: this.header.id,
                tgl_kalibrasi: "",
                pemeriksa_id: "",
                hasil: "",
                produk: this.productSelected,
                gantiAlamat: 'false',
                jenis_pemilik: JSON.parse(JSON.stringify(this.header.jenis_pemilik)),
            },
            pemilik: [],
            dateMax: new Date().toISOString().split("T")[0],
            showJenisPemilik: false,
        }
    },
    components: {
        Table,
    },
    methods: {
        close() {
            this.$emit("close");
            $(".modalKalibrasi").modal("hide");
        },
        async getKaryawan() {
            const { data: karyawan } = await axios.get('/api/karyawan_all')
            const { data: kepemilikan } = await axios.get("/api/labs/kode_milik").then(res => res.data)
            this.karyawan = karyawan
            this.pemilik = kepemilikan.map((data) => {
                return {
                    value: data.id,
                    label: data.nama,
                };
            });
            if (this.header.jenis_pemilik.length > 0 || this.header.jenis_pemilik?.label) {
                this.showJenisPemilik = true
            }
        },
        async simpan() {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil disimpan.', 'success')
                this.close()
                this.$emit('refresh')
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal disimpan.', 'error')
            }

            let formData = {
                ...this.header,
                ...this.form
            }

            try {
                const { data } = await axios.post('/api/labs/uji', formData).then(success).catch(error)
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        this.getKaryawan()
    },
};
</script>
<template>
    <div class="modal fade modalKalibrasi" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="overflow: hidden;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Form Kalibrasi
                    </h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="" class="col-5 col-form-label text-right">Tanggal Kalibrasi</label>
                                <div class="col-5">
                                    <input type="date" class="form-control" v-model="form.tgl_kalibrasi" :max="dateMax">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 col-form-label text-right">Nama Teknisi</label>
                                <div class="col-5">
                                    <v-select :options="karyawan" :reduce="karyawan => karyawan.id" label="nama"
                                        v-model="form.pemeriksa_id"></v-select>
                                </div>
                            </div>
                            <div class="form-group row" v-if="!showJenisPemilik">
                                <label for="" class="col-5 col-form-label text-right">Jenis Pemilik</label>
                                <div class="col-5">
                                    <v-select :options="pemilik" v-model="form.jenis_pemilik"></v-select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 col-form-label text-right">Hasil Kalibrasi</label>
                                <div class="col-5">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="customRadioInline"
                                            class="custom-control-input" value="ok" v-model="form.hasil" />
                                        <label class="custom-control-label" for="customRadioInline1">ALAT BAIK DAN LAIK
                                            DIGUNAKAN</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="customRadioInline"
                                            class="custom-control-input" value="nok" v-model="form.hasil" />
                                        <label class="custom-control-label" for="customRadioInline2">ALAT TIDAK LAIK
                                            PAKAI</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-lg-5 col-md-12 col-form-label text-right">Alamat Sertifikat</label>
                                <div class="col-lg-6 col-md-12 col-form-label">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="false" v-model="form.gantiAlamat">
                                        <label class="form-check-label" for="inlineRadio1">Sama dengan penjualan</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="true" v-model="form.gantiAlamat">
                                        <label class="form-check-label" for="inlineRadio2">Ubah Alamat</label>
                                    </div>
                                    <textarea class="form-control" v-model="header.alamat" cols="5"
                                        :disabled="form.gantiAlamat == 'false'"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Table :dataTable="productSelected"></Table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="close">
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="simpan">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
