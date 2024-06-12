<script>
import axios from 'axios';
export default {
    props: ["meeting"],
    data() {
        return {
            form: {
                isi: JSON.parse(JSON.stringify(this.meeting.isi)),
                status: JSON.parse(JSON.stringify(this.meeting.hasil)),
                catatan: JSON.parse(JSON.stringify(this.meeting.catatan)),
            },
        }
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalNotulen").modal("hide");
            });
            this.$emit("closeModal");
        },
        save() {
            const cekArrayNull = Object.values(this.form).some(
                (val) => val === null || val === ""
            );

            if (cekArrayNull) {
                swal.fire({
                    title: "Peringatan",
                    text: "Data tidak boleh kosong",
                    icon: "warning",
                });
                return;
            }

            const form = {
                ...this.form,
                ...this.meeting,
            }

            axios.post('/api/hr/meet/notulen/verif', form, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('lokal_token'),
                },
            }).then((res) => {
                swal.fire({
                    title: "Berhasil",
                    text: "Data berhasil disimpan",
                    icon: "success",
                });
                this.closeModal();
                this.$emit("refresh");
            }).catch((err) => {
                swal.fire({
                    title: "Gagal",
                    text: "Terjadi kesalahan",
                    icon: "error",
                });
            });
        },
    },
    watch: {
        'form.status': {
            handler: function (val) {
                if (val == "sesuai") {
                    delete this.form.catatan;
                    delete this.meeting.catatan;
                } else {
                    this.$set(this.form, "catatan", "");                    
                } 
            },
            deep: true,
        },
    },
};
</script>
<template>
    <div class="modal fade modalNotulen" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kesesuaian</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Uraian</label>
                        <textarea class="form-control" v-model="form.isi" disabled></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2">Hasil</label>
                        <div class="col-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    v-model="form.status" id="inlineRadio1" value="sesuai" />
                                <label class="form-check-label" for="inlineRadio1">Sesuai</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    v-model="form.status" id="inlineRadio2" value="tidak_sesuai" />
                                <label class="form-check-label" for="inlineRadio2">Tidak Sesuai</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="form.status == 'tidak_sesuai'">
                        <label for="">Catatan</label>
                        <textarea class="form-control" v-model="form.catatan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="save">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>