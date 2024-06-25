<script>
import axios from "axios";
import VueSelect from "vue-select";
export default {
    components: {
        VueSelect,
    },
    data() {
        return {
            karyawan: [],
            meeting: {
                judul: "",
                notulen: "",
                moderator: "",
                deskripsi: "",
                tanggal: "",
                mulai: "",
                selesai: "",
                lokasi: "",
                peserta: [],
                pimpinan: "",
            },
            hourRangeAkhir: [0, 23],
            lokasiMeeting: [],
            selectedParticipants: [],
        };
    },
    methods: {
        closeModal() {
            $(".modalMeetingBaru").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        inputNumberOnly(e) {
            const re = /[0-9]+/g;
            if (!re.test(e.key)) {
                e.preventDefault();
            }
        },
        async getDataKaryawan() {
            try {
                const { data: karyawan } = await axios.get("/api/karyawan_all");
                const { data: lokasi } = await axios.get(
                    "/api/hr/meet/lokasi/show"
                );
                const { data: notulen } = await axios.get(
                    "/api/hr/meet/getKaryawan",
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "lokal_token"
                            )}`,
                        },
                    }
                );

                this.meeting.notulen = notulen.data.id;

                this.karyawan = karyawan;
                this.lokasiMeeting = lokasi.map((item) => {
                    return {
                        id: item.id,
                        label: item.nama,
                    };
                });
            } catch (error) {
                console.log(error);
            }
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
                this.hourRangeAkhir = [0, 23];
            }
        },
        async simpan() {
            // cek apakah ada data yang kosong atau array kosong
            const cekNotNull = Object.values(this.meeting).every((val) => {
                if (Array.isArray(val)) {
                    return val.length > 0;
                }
                return val !== "" && val !== null;
            });

            if (!cekNotNull) {
                this.$swal({
                    title: "Perhatian!",
                    text: "Data tidak boleh kosong",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                return;
            }

            if (this.meeting.mulai > this.meeting.selesai) {
                this.$swal({
                    title: "Perhatian!",
                    text: "Jam mulai tidak boleh lebih besar dari jam selesai",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                this.meeting.mulai = "";
                return;
            }

            if (this.meeting.selesai < this.meeting.mulai) {
                this.$swal({
                    title: "Perhatian!",
                    text: "Jam selesai tidak boleh lebih kecil dari jam mulai",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                this.meeting.selesai = "";
                return;
            }

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
                ...this.meeting,
                peserta,
            };

            try {
                await axios.post("/api/hr/meet/jadwal", form, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "lokal_token"
                        )}`,
                    },
                });
                swal.fire({
                    title: "Berhasil!",
                    text: "Berhasil menyimpan data",
                    icon: "success",
                    confirmButtonText: "OK",
                });
                this.$emit("refresh");
                this.closeModal();
            } catch {
                swal.fire({
                    title: "Gagal!",
                    text: "Gagal menyimpan data",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
    computed: {
        // filter karyawan jika sudah menjadi notulen, moderator, pimpinan rapat. tidak usah muncul di peserta dan peserta yang sudah dipilih tidak ada di option
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
    },
};
</script>
<template>
    <div
        class="modal fade modalMeetingBaru"
        id="modelId"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Meeting Baru</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Judul Meeting</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="meeting.judul"
                        />
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi / Agenda Meeting</label>
                        <textarea
                            class="form-control"
                            v-model="meeting.deskripsi"
                        ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal Meeting</label>
                        <input
                            type="date"
                            class="form-control"
                            :min="new Date().toISOString().split('T')[0]"
                            v-model="meeting.tanggal"
                        />
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
                                :disabled="meeting.mulai === ''"
                            />
                        </div>
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
                        <label for="">Lokasi Meeting</label>
                        <vue-select
                            :reduce="(lokasi) => lokasi.id"
                            :options="lokasiMeeting"
                            v-model="meeting.lokasi"
                        />
                    </div>
                    <div class="form-group">
                        <label for="">Peserta Meeting</label>
                        <!-- disabled jika notulen, moderator, pimpinan rapat belum diisi -->
                        <vue-select
                            multiple
                            :options="karyawanFilteredPeserta"
                            label="nama"
                            :disabled="
                                meeting.notulen === '' ||
                                meeting.moderator === '' ||
                                meeting.pimpinan === '' ||
                                meeting.notulen == null ||
                                meeting.moderator == null ||
                                meeting.pimpinan == null
                            "
                            v-model="selectedParticipants"
                            @input="meeting.peserta = selectedParticipants.map((item) => item.id)"
                        />
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
                        @click="simpan"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
