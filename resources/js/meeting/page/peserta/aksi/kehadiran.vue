<script>
import uploadFile from '../../../components/uploadFile.vue';
export default {
    props: ["kehadiran"],
    components: {
        uploadFile,
    },
    data() {
        return {
            form: {
                kehadiran: JSON.parse(JSON.stringify(this.kehadiran.status_peserta)) == 'belum_mengisi_daftar_hadir' ? "" : JSON.parse(JSON.stringify(this.kehadiran.status_peserta)),
            },
            loading: false,
        }
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalKehadiran").modal("hide");
            });
            this.$emit("closeModal");
        },
        save() {
            this.loading = true;
            // detected form data is empty or not this.form.file = null
            const isFormEmpty = Object.values(this.form).some((item) => {
                if (Array.isArray(item)) {
                    if (item.length === 0) {
                        return true;
                    } else {
                        return item.some((subItem) => {
                            return Object.values(subItem).some((subSubItem) => {
                                return subSubItem == "" || subSubItem == null;
                            });
                        });
                    }
                } else {
                    return item == "";
                }
            });

          
            if (isFormEmpty) {
                this.$swal("Gagal", "Form tidak boleh kosong", "error");
                this.loading = false;
                return;
            }

            let formData = new FormData();

            let form = {
                ...this.kehadiran,
                ...this.form,
            }

            if (this.form?.dokumentasi) {
                form = {
                    ...form,
                    dokumentasi: this.form.dokumentasi.map((file) => {
                        return {
                            file
                        }
                    }),
                }
            }

            for (let key in form) {
                // detect when array is key form.dokumentasi foreach with formData
                if (Array.isArray(form[key])) {
                    form[key].forEach((item, index) => {
                        for (let keyItem in item) {
                            formData.append(`${key}[${index}][${keyItem}]`, item[keyItem]);
                        }
                    });
                } else {
                    formData.append(key, form[key]);
                }
            }

            this.$_post('/api/hr/meet/jadwal_person/kehadiran', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }).then((response) => {
                this.$swal("Berhasil", "Data berhasil disimpan", "success");
                this.$emit("refresh");
                this.closeModal();
            }).catch((error) => {
                this.$swal("Gagal", "Data gagal disimpan", "error");
            }).finally(() => {
                this.loading = false;
            });
        },
        uploadFileData(file) {
            this.form.dokumentasi = file;
        },
    },
    watch: {
        'form.kehadiran': {
            handler: function (val) {
                if (val == 'hadir') {
                    delete this.form.dokumentasi;
                    delete this.form.alasan;
                } else {
                    this.$set(this.form, 'dokumentasi', []);
                    this.$set(this.form, 'alasan', null);
                }
            },
            deep: true,
        },
    }
};
</script>
<template>
    <div class="modal fade modalKehadiran" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" :class="form.kehadiran == 'tidak_hadir' ? 'modal-xl' : 'modal-lg'" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kehadirans</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-4">Kehadiran</label>
                        <div class="col-8">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.kehadiran"
                                    id="inlineCheckbox1" value="hadir" />
                                <label class="form-check-label" for="inlineCheckbox1">Hadir</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.kehadiran"
                                    id="inlineCheckbox2" value="tidak_hadir" />
                                <label class="form-check-label" for="inlineCheckbox2">Tidak Hadir</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" v-if="form.kehadiran == 'tidak_hadir'">
                        <label for="" class="col-4">Alasan</label>
                        <div class="col-8">
                            <textarea class="form-control" v-model="form.alasan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row" v-if="form.kehadiran == 'tidak_hadir'">
                        <label for="" class="col-4">Dokumen Pendukung</label>
                        <div class="col-8">
                            <uploadFile @changed="uploadFileData" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="save" :disabled="loading">
                        <div class="spinner-border spinner-border-sm" role="status" v-if="loading">
                            <span class="sr-only">Loading...</span>
                        </div>
                        {{ loading ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>