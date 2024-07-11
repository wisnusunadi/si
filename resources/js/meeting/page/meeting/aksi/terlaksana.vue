<script>
import dokumentasi from "./dokumentasi.vue";
import VueSelect from "vue-select";
import uploadFile from "../../../components/uploadFile.vue";
export default {
    components: {
        VueSelect,
        dokumentasi,
        uploadFile,
    },
    props: ["meeting"],
    data() {
        return {
            cloneMeeting: JSON.parse(JSON.stringify(this.meeting)),
            form: {
                dokumentasi: [],
                notulensi: [
                    {
                        pic: "",
                        isi: "",
                    },
                ],
                hasil: [
                    {
                        isi: "",
                    },
                ],
            },
            imgs: [],
            karyawan: [],
            hourRangeAkhir: [],
            loading: false,
            lokasiMeeting: [],
            selectedParticipants: [],
        };
    },
    methods: {
        closeModal() {
            $(".modalterlaksana").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        async getDataKaryawan() {
            try {
                const { data: karyawan } = await this.$_get(
                    "/api/karyawan_all"
                );
                const { data: lokasi } = await this.$_get(
                    "/api/hr/meet/lokasi/show"
                );
                this.karyawan = karyawan;
                this.lokasiMeeting = lokasi.map((item) => {
                    return {
                        id: item.id,
                        label: item.nama,
                    };
                });

                this.meeting.peserta = this.meeting.peserta
                    .map((peserta) => {
                        if (peserta.kehadiran == "hadir") {
                            return peserta.id;
                        } else {
                            // jika peserta tidak hadir, hapus dari peserta
                            return null;
                        }
                    })
                    .filter((item) => item !== null);

                // remove peserta jika sudah ada di notulen, moderator, pimpinan
                this.meeting.peserta = this.meeting.peserta.filter(
                    (peserta) => {
                        return (
                            peserta !== this.meeting.notulen &&
                            peserta !== this.meeting.moderator &&
                            peserta !== this.meeting.pimpinan
                        );
                    }
                );

                this.selectedParticipants = this.meeting.peserta.map(
                    (peserta) => {
                        return this.karyawan.find(
                            (item) => item.id === peserta
                        );
                    }
                );
            } catch (error) {
                console.log(error);
            }
        },
        tambahpic() {
            this.form.notulensi.push({
                pic: "",
                isi: "",
            });
        },
        tambahhasil() {
            this.form.hasil.push({
                isi: "",
            });
        },
        calculateHourAkhir() {
            if (this.meeting.mulai !== "") {
                const waktu_awal = this.meeting.mulai.split(":");
                const hour = [];
                for (let i = waktu_awal[0]; i <= 23; i++) {
                    hour.push(i);
                }
                this.hourRangeAkhir = hour;
            } else {
                this.hourRangeAkhir = [];
            }
        },
        async save() {
            // kalkulasi limit upload file total 800mb
            let totalSize = 0;
            for (let i = 0; i < this.file.length; i++) {
                totalSize += this.file[i].size;
            }

            if (totalSize > 800000000) { // satuan byte
                this.$swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Total ukuran file tidak boleh melebihi 800MB",
                });
                return;
            }
            // hapus jika di hasil notulensi satu baris pic dan isi kosong
            this.form.notulensi = this.form.notulensi.filter(
                (item) => item.pic !== "" || item.isi !== ""
            );

            // hapus hasil rapat jika isi kosong
            this.form.hasil = this.form.hasil.filter((item) => item.isi !== "");

            // jika form notulensi array kosong, tambahkan satu baris
            if (this.form.notulensi.length === 0) {
                this.form.notulensi.push({
                    pic: "",
                    isi: "",
                });
            }

            // jika form hasil array kosong, tambahkan satu baris
            if (this.form.hasil.length === 0) {
                this.form.hasil.push({
                    isi: "",
                });
            }

            // check is form not empty is "" and array length is 0
            const emptyFields = [];
            const isFormEmpty = Object.entries(this.form).some(
                ([key, item]) => {
                    if (Array.isArray(item)) {
                        if (item.length === 0) {
                            emptyFields.push(key);
                            return true;
                        } else {
                            return item.some((subItem, index) => {
                                const subEmptyFields = [];
                                const isSubItemEmpty = Object.entries(
                                    subItem
                                ).some(([subKey, subItem]) => {
                                    if (subItem === "") {
                                        subEmptyFields.push(subKey);
                                        return true;
                                    }
                                    return false;
                                });
                                if (isSubItemEmpty) {
                                    emptyFields.push(
                                        `${key} baris ${
                                            index + 1
                                        } ${subEmptyFields.join(", ")}`
                                    );
                                }
                                return isSubItemEmpty;
                            });
                        }
                    } else {
                        if (item === "") {
                            emptyFields.push(key);
                        }
                    }
                }
            );

            console.log("error");

            if (isFormEmpty) {
                this.$swal(
                    "Gagal",
                    `Form ${emptyFields.join(", ")} tidak boleh kosong`,
                    "error"
                );
                return;
            }

            if (this.imgs == 0) {
                this.$swal(
                    "Gagal",
                    "Silahkan tunggu proses upload hingga selesai",
                    "error"
                );
                return;
            }

            if (this.meeting.peserta.length === 0) {
                this.$swal("Gagal", "Peserta tidak boleh kosong", "error");
                return;
            }

            let formData = new FormData();

            let peserta = [
                ...this.meeting.peserta,
                this.meeting.notulen,
                this.meeting.moderator,
                this.meeting.pimpinan,
            ];

            // filter peserta jika ada yang sama
            peserta = peserta.filter(
                (item, index) => peserta.indexOf(item) === index
            );

            let form = {
                ...this.meeting, // Add all entries from this.meeting
                ...this.form, // Add all entries from this.form
                peserta,
                dokumentasi: this.form.dokumentasi.map((file) => {
                    return {
                        file,
                    };
                }),
            };

            // Iterate over form data and append to formData
            for (let key in form) {
                // Detect when key is an array
                if (Array.isArray(form[key])) {
                    form[key].forEach((item, index) => {
                        // If the item is an object, iterate over its properties
                        if (typeof item === "object" && item !== null) {
                            for (let keyItem in item) {
                                formData.append(
                                    `${key}[${index}][${keyItem}]`,
                                    item[keyItem]
                                );
                            }
                        } else {
                            // If the item is a primitive type (e.g., number or string), directly append it
                            formData.append(`${key}[${index}]`, item);
                        }
                    });
                } else {
                    formData.append(key, form[key]);
                }
            }

            this.loading = true;

            const { success, message } = await this.$_post(
                "/api/hr/meet/jadwal/update/terlaksana",
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
            this.loading = false;

            if (!success) {
                this.$swal("Gagal", message, "error");
                return;
            }

            this.closeModal();
            this.$emit("refresh");
            this.$swal("Berhasil", "Data berhasil disimpan", "success");
        },
        uploadDokumen(file, imgs) {
            this.form.dokumentasi = file;
            this.imgs = imgs;
            console.log("file", file, "imgs", imgs);
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
    computed: {
        showKeteranganKetidaksesuaian() {
            if (
                this.meeting.tanggal != this.cloneMeeting.tanggal ||
                this.meeting.lokasi != this.cloneMeeting.lokasi ||
                this.meeting.mulai != this.cloneMeeting.mulai ||
                this.meeting.selesai != this.cloneMeeting.selesai
            ) {
                return true;
            } else {
                return false;
            }
        },
        // filter karyawan jika sudah menjadi notulen, moderator, pimpinan rapat. tidak usah muncul di peserta
        karyawanFilteredPeserta() {
            return this.karyawan.filter((item) => {
                return (
                    item.id !== this.meeting.notulen &&
                    item.id !== this.meeting.moderator &&
                    item.id !== this.meeting.pimpinan &&
                    !this.meeting.peserta.includes(item.id)
                );
            });
        },
        karyawanFilteredNonPeserta() {
            // filter karyawan yang belum ada di peserta
            return this.karyawan.filter((item) => {
                return !this.meeting.peserta.includes(item.id);
            });
        },
        // gabungan peserta, notulen, moderator, pimpinan
        karyawanPeserta() {
            return this.karyawan.filter((item) => {
                return (
                    item.id === this.meeting.notulen ||
                    item.id === this.meeting.moderator ||
                    item.id === this.meeting.pimpinan ||
                    this.meeting.peserta.includes(item.id)
                );
            });
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalterlaksana"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-xl modal-dialog-scrollable"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ meeting.nama }}</h5>
                    <button
                        :disabled="loading"
                        type="button"
                        class="close"
                        @click="closeModal"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="!loading">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="tanggal" class="col-form-label"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                v-model="meeting.tanggal"
                                :min="new Date().toISOString().split('T')[0]"
                            />
                        </div>
                        <div class="col-sm-6">
                            <label for="tanggal" class="col-form-label"
                                >Lokasi</label
                            >
                            <vue-select
                                :reduce="(lokasi) => lokasi.id"
                                :options="lokasiMeeting"
                                v-model="meeting.lokasi"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mulai" class="col-sm-2 col-form-label"
                            >Jam</label
                        >
                        <div class="col-sm-4">
                            <vue-timepicker
                                v-model="meeting.mulai"
                                input-width="100%"
                                autocomplete="on"
                                @input="calculateHourAkhir"
                            />
                        </div>
                        -
                        <div class="col-sm-4">
                            <vue-timepicker
                                v-model="meeting.selesai"
                                input-width="100%"
                                autocomplete="on"
                                :hour-range="hourRangeAkhir"
                            />
                        </div>
                    </div>
                    <div
                        class="form-group"
                        v-if="showKeteranganKetidaksesuaian"
                    >
                        <label for="">Keterangan Ketidaksesuaian</label>
                        <textarea
                            class="form-control"
                            v-model="form.keteranganketidaksesuaian"
                        ></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label for="">Notulen</label>
                            <vue-select
                                :options="karyawanFilteredNonPeserta"
                                label="nama"
                                :reduce="(karyawan) => karyawan.id"
                                v-model="meeting.notulen"
                                disabled
                            />
                        </div>
                        <div class="col">
                            <label for="">Moderator</label>
                            <vue-select
                                :options="karyawanFilteredNonPeserta"
                                label="nama"
                                :reduce="(karyawan) => karyawan.id"
                                v-model="meeting.moderator"
                            />
                        </div>
                        <div class="col">
                            <label for="">Pimpinan Rapat</label>
                            <vue-select
                                :options="karyawanFilteredNonPeserta"
                                label="nama"
                                :reduce="(karyawan) => karyawan.id"
                                v-model="meeting.pimpinan"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Peserta Meeting</label>
                        <vue-select
                            multiple
                            :options="karyawanFilteredPeserta"
                            label="nama"
                            v-model="selectedParticipants"
                            @input="
                                meeting.peserta = selectedParticipants.map(
                                    (item) => item.id
                                )
                            "
                        />
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Hasil Notulensi</label
                        >
                        <div class="col-sm-10">
                            <div
                                v-for="(notulen, idx) in form.notulensi"
                                class="row mb-1"
                            >
                                <div class="col-sm-4">
                                    <vue-select
                                        v-model="notulen.pic"
                                        :options="karyawanPeserta"
                                        label="nama"
                                        :reduce="(karyawan) => karyawan?.id"
                                        placeholder="penanggung jawab"
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <textarea
                                        class="form-control"
                                        v-model="notulen.isi"
                                        placeholder="Isi Notulensi"
                                    ></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button
                                        class="btn btn-danger"
                                        @click="form.notulensi.splice(idx, 1)"
                                    >
                                        x
                                    </button>
                                    <button
                                        v-if="idx === form.notulensi.length - 1"
                                        class="btn btn-primary"
                                        @click="tambahpic"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Hasil Rapat</label
                        >
                        <div class="col-sm-10">
                            <div v-for="(hasil, idx) in form.hasil" class="row">
                                <div class="col-10">
                                    <textarea
                                        class="form-control mb-2"
                                        v-model="hasil.isi"
                                    ></textarea>
                                </div>
                                <div class="col-2">
                                    <button
                                        class="btn btn-danger"
                                        @click="form.hasil.splice(idx, 1)"
                                    >
                                        x
                                    </button>
                                    <button
                                        v-if="idx === form.hasil.length - 1"
                                        class="btn btn-primary"
                                        @click="tambahhasil"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Dokumentasi</label
                        >
                        <uploadFile :maxTotalSize="838860800" @changed="uploadDokumen" />
                    </div>
                </div>
                <div class="modal-body text-center" v-else>
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
                        :disabled="loading"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        :disabled="loading"
                        @click="save"
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
