<script>
import uploadFile from "../../../components/uploadFile.vue";
export default {
    props: ["kehadiran"],
    components: {
        uploadFile,
    },
    data() {
        return {
            form: {
                kehadiran:
                    JSON.parse(
                        JSON.stringify(this.kehadiran.status_kehadiran)
                    ) == "belum_mengisi_daftar_hadir"
                        ? ""
                        : JSON.parse(
                              JSON.stringify(this.kehadiran.status_kehadiran)
                          ),
            },
            loading: false,
            maxTotalSize: 629145600, // 800MB
        };
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalKehadiran").modal("hide");
            });
            this.$emit("closeModal");
        },
        changeByteToMegaByte(byte) {
            return (byte / 1024 / 1024).toFixed(2);
        },
        save() {
            this.loading = true;
            if (this.checkSize > this.maxTotalSize) {
                // satuan byte
                this.$swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `Total ukuran file didalam form tidak boleh melebihi ${this.changeByteToMegaByte(
                        this.maxTotalSize
                    )} MB`,
                });
                return;
            }
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
            };

            if (this.form?.dokumentasi) {
                form = {
                    ...form,
                    dokumentasi: this.form.dokumentasi.map((file) => {
                        return {
                            file,
                        };
                    }),
                };
            }

            for (let key in form) {
                // detect when array is key form.dokumentasi foreach with formData
                if (Array.isArray(form[key])) {
                    form[key].forEach((item, index) => {
                        for (let keyItem in item) {
                            formData.append(
                                `${key}[${index}][${keyItem}]`,
                                item[keyItem]
                            );
                        }
                    });
                } else {
                    formData.append(key, form[key]);
                }
            }

            this.$_post("/api/hr/meet/jadwal_person/kehadiran", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .then((response) => {
                    this.$swal("Berhasil", "Data berhasil disimpan", "success");
                    this.$emit("refresh");
                    this.closeModal();
                })
                .catch((error) => {
                    this.$swal("Gagal", "Data gagal disimpan", "error");
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        uploadFileData(file) {
            this.form.dokumentasi = file;
        },
    },
    computed: {
        checkSize() {
            if (this.form.dokumentasi) {
                let totalSize = 0;
                for (let i = 0; i < this.form.dokumentasi.length; i++) {
                    totalSize += this.form.dokumentasi[i].size;
                }

                return this.maxTotalSize - totalSize;
            } else {
                return this.maxTotalSize;
            }
        },
    },
    watch: {
        "form.kehadiran": {
            handler: function (val) {
                if (val == "hadir") {
                    delete this.form.dokumentasi;
                    delete this.form.alasan;
                } else {
                    this.$set(this.form, "dokumentasi", []);
                    this.$set(this.form, "alasan", null);
                }
            },
            deep: true,
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalKehadiran"
        id="staticBackdrop"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog"
            :class="form.kehadiran == 'tidak_hadir' ? 'modal-xl' : 'modal-lg'"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kehadiran</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="!loading">
                    <div class="form-group row">
                        <label class="col-4">Kehadiran</label>
                        <div class="col-8">
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    v-model="form.kehadiran"
                                    id="inlineCheckbox1"
                                    value="hadir"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineCheckbox1"
                                    >Hadir</label
                                >
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    v-model="form.kehadiran"
                                    id="inlineCheckbox2"
                                    value="tidak_hadir"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineCheckbox2"
                                    >Tidak Hadir</label
                                >
                            </div>
                        </div>
                    </div>
                    <div
                        class="form-group row"
                        v-if="form.kehadiran == 'tidak_hadir'"
                    >
                        <label for="" class="col-4">Alasan</label>
                        <div class="col-8">
                            <textarea
                                class="form-control"
                                v-model="form.alasan"
                            ></textarea>
                        </div>
                    </div>
                    <div
                        class="form-group row"
                        v-if="form.kehadiran == 'tidak_hadir'"
                    >
                        <label for="" class="col-4">Dokumen Pendukung</label>
                        <div class="col-8">
                            <uploadFile @changed="uploadFileData" />
                            <p class="text-muted">
                                <span class="text-danger">*</span> Total ukuran
                                file tidak boleh melebihi
                                {{ changeByteToMegaByte(checkSize) }} MB
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="modal-body text-center"
                    v-if="loading && checkSize > 0"
                >
                    <div class="d-flex justify-content-center">
                        <script
                            src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
                            type="module"
                        ></script>
                        <dotlottie-player
                            src="https://lottie.host/19bf105b-8bab-4166-a398-5737953bf1e0/YXTnQ5N482.json"
                            background="transparent"
                            speed="1"
                            style="width: 300px; height: 300px"
                            loop
                            autoplay
                        ></dotlottie-player>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="save"
                        :disabled="loading"
                    >
                        <div
                            class="spinner-border spinner-border-sm"
                            role="status"
                            v-if="loading"
                        >
                            <span class="sr-only">Loading...</span>
                        </div>
                        {{ loading ? "Menyimpan..." : "Simpan" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
