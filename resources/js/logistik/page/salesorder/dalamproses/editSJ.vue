<script>
import axios from 'axios';
export default {
    props: ['form'],
    data() {
        return {
            formEdit: {
                jenis_sj_edit: '',
                no_invoice_sj_edit: '',
                tgl_kirim_edit_sj: '',
            }
        }
    },
    methods: {
        closeModal() {
            $('.modalEditSJ').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        simpan() {
            axios.post('/api/logistik/so/update_draft', {
                id: this.form.id,
                sj: `${this.formEdit.jenis_sj_edit}${this.formEdit.no_invoice_sj_edit}`,
                tgl_sj: this.formEdit.tgl_kirim_edit_sj
            }).then((response) => {
                this.closeModal();
                this.$emit('refresh');
            }).catch((error) => {
                console.log(error);
            });
        },
        cekSplit(sj) {
            function findSeparator(sj) {
                let separator = '';
                if (sj.includes('-')) {
                    separator = '-';
                } else if (sj.includes('.')) {
                    separator = '.';
                } else if (sj.includes('NBT')) {
                    separator = 'NBT';
                }
                return separator;
            }

            let separator = findSeparator(sj);
            if (separator) {
                let split = sj.split(separator);
                this.formEdit.jenis_sj_edit = split[0] + separator;
                this.formEdit.no_invoice_sj_edit = split[1];
            }
        }
    },
    watch: {
        form: {
            handler: function (val) {
                // pecah strip awal di val.sj
                this.cekSplit(val.sj);
                // ubah form.isi jadi json dan ambil tgl_sj
                let isi = JSON.parse(val.isi);
                this.formEdit.tgl_kirim_edit_sj = isi.tgl_sj;
            },
            immediate: true
        }
    }
}
</script>
<template>
    <div class="modal fade modalEditSJ" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Surat Jalan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h5>Data Pengiriman</h5>
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No
                                        Surat Jalan</label>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="input-group mb-3 sj_baru" id="sj_baru" name="sj_baru">
                                            <div class="input-group-prepend">
                                                <select class="form-control jenis_sj" v-model="formEdit.jenis_sj_edit"
                                                    id="jenis_sj_edit">
                                                    <option value="SPA-">SPA</option>
                                                    <option value="B.">B</option>
                                                    <option value="NBT">NBT</option>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control col-form-label" id="no_invoice"
                                                v-model="formEdit.no_invoice_sj_edit">
                                            <div class="invalid-feedback" id="msgnoinvoice"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-5 col-md-12 labelket" for="tgl_kirim">Tanggal
                                        Pengiriman</label>
                                    <div class="col-lg-4 col-md-6">
                                        <input type="date" class="form-control col-form-label"
                                            v-model="formEdit.tgl_kirim_edit_sj" id="tgl_kirim">
                                        <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <button type="button" class="btn btn-danger" @click="closeModal">
                                        Batal
                                    </button>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button type="button" class="btn btn-primary" @click="simpan">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>