<script>
import axios from 'axios'
import statusComponents from '../../components/status.vue'
export default {
    props: ['doData'],
    components: {
        statusComponents
    },
    data() {
        return {
            form: {
                no_urut: '',
                no_do: '',
                tgl_do: '',
                keterangan: '',
            }
        }
    },
    methods: {
        // Close modal
        closeModal() {
            $('.modalDO').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        // Save the changes
        simpan() {
            const cekNotNull = Object.keys(this.form).filter(key => this.form[key] === '')
            if (cekNotNull.length > 0) {
                this.$swal('Peringatan', 'Data tidak boleh kosong', 'warning')
                return
            }

            axios.put(`/api/penjualan/pesanan/update/${this.doData.pesanan_id}/${this.doData.jenis}`, this.form)
                .then(res => {
                    this.closeModal()
                    this.$emit('refresh')
                }).catch(err => {
                    console.log(err)
                })
        }
    },
    watch: {
        doData: {
            handler: function (val) {
                if (val.jenis == 'ekatalog') {
                    this.form.no_urut = val.no_urut
                    this.form.no_do = val?.pesanan?.no_do
                    this.form.tgl_do = val?.pesanan?.tgl_do
                    this.form.keterangan = val?.pesanan?.ket
                } else {
                    this.form.no_do = val?.pesanan?.no_do
                    this.form.tgl_do = val?.pesanan?.tgl_do
                    this.form.keterangan = val?.pesanan?.ket
                    delete this.form.no_urut
                }
            },
            immediate: true
        }
    }
}
</script>
<template>
    <div class="modal fade modalDO" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Pesanan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="card">
                                    <div class="card-header">Info Pesanan</div>
                                    <div class="card-body">
                                        <div class="margin">
                                            <a class="text-muted">No SO</a>
                                            <b class="float-right"> {{ doData.pesanan.so }} </b>
                                        </div>

                                        <div class="margin" v-if="doData.jenis == 'ekatalog'">
                                            <a class="text-muted">No AKN</a>
                                            <b class="float-right">{{ doData.no_paket }}</b>
                                        </div>

                                        <div class="margin">
                                            <a class="text-muted">No PO</a>
                                            <b class="float-right">
                                                {{ doData.pesanan.no_po }}
                                            </b>
                                        </div>

                                        <div class="margin">
                                            <a class="text-muted">Status</a>
                                            <b class="float-right" id="status">
                                                <statusComponents
                                                    :status="doData.jenis == 'ekatalog' ? doData.status : doData.pesanan.state.nama" />
                                            </b>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header"><i class="fas fa-edit"></i> Ubah No Urut</div>
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row" v-if="doData.jenis == 'ekatalog'">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No
                                                        Urut</label>
                                                    <div class="col-lg-3 col-md-12">
                                                        <input type="number" class="form-control col-form-label"
                                                            v-model="form.no_urut" id="no_urut">
                                                        <div class="invalid-feedback" id="msgno_urut"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label"
                                                            id="no_do" v-model="form.no_do">
                                                        <div class="invalid-feedback" id="msgno_do"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for=""
                                                        class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="date" class="form-control col-form-label"
                                                            v-model="form.tgl_do" id="tgl_do" value="">
                                                        <div class="invalid-feedback" id="msgtgl_do"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for=""
                                                        class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <textarea class="form-control col-form-label"
                                                            v-model="form.keterangan" id="keterangan"></textarea>
                                                        <div class="invalid-feedback" id="msgtgl_do"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <span class="float-left"><button type="button" class="btn btn-danger"
                                                    @click="closeModal">Batal</button></span>
                                            <span class="float-right"><button @click="simpan"
                                                    class="btn btn-warning float-right"
                                                    id="btnsimpan">Simpan</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.margin {
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 15px;
    margin-bottom: 15px;
}
</style>
