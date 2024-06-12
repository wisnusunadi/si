<script>
import VueSelect from "vue-select";
import axios from "axios";
export default {
    props: ["meeting"],
    components: {
        VueSelect,
    },
    data() {
        return {
            karyawan: [],
            hourRangeAkhir: [0, 23],
            lokasiMeeting: [],
        };
    },
    methods: {
        closeModal() {
            $(".modalMeetingEdit").modal("hide");
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
        async getDataKaryawan() {
            try {
                const { data: karyawan } = await axios.get("/api/karyawan_all");
                const { data: lokasi } = await axios.get(
                    "/api/hr/meet/lokasi/show"
                );
                this.karyawan = karyawan;
                this.lokasiMeeting = lokasi.map((item) => {
                    return {
                        id: item.id,
                        label: item.nama,
                    };
                });

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
            } catch (error) {
                console.log(error);
            } finally {
                this.meeting.alasan = "";
            }
        },
        simpan() {
            this.$delete(this.meeting, "lokasi_nama");
            this.$delete(this.meeting, "dokumen_meet");
            this.$delete(this.meeting, "hasil_notulen");
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
                axios.put(`/api/hr/meet/jadwal/${this.meeting?.id}`, form, {
                    headers: {
                        "Authorization": `Bearer ${localStorage.getItem("lokal_token")}`,
                    }
                });
                this.closeModal();
                this.$emit("refresh");
                this.$swal(
                    "Berhasil!",
                    "Jadwal Meeting berhasil diubah",
                    "success"
                );
            } catch (error) {
                console.log(error);
                this.$swal("Gagal!", "Jadwal Meeting gagal diubah", "error");
            }
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
    computed: {
        karyawanFilteredPeserta() {
            return this.karyawan.filter((item) => {
                return (
                    item.id !== this.meeting.notulen &&
                    item.id !== this.meeting.moderator &&
                    item.id !== this.meeting.pimpinan
                );
            });
        },
        karyawanFilteredNonPeserta() {
            // filter karyawan yang belum ada di peserta
            return this.karyawan.filter((item) => {
                return !this.meeting.peserta.includes(item.id);
            });
        },
    }
};
</script>
<template>
    <div
        class="modal fade modalMeetingEdit"
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
                    <h5 class="modal-title">Edit Jadwal Meeting</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    <!-- diubah ke peserta seperti tambah dan value jumlah peserta menghitung array peserta -->
                    <div class="form-group">
                        <label for="">Peserta Meeting</label>
                        <vue-select
                            multiple
                            :options="karyawanFilteredPeserta"
                            label="nama"
                            :reduce="(karyawan) => karyawan.id"
                            v-model="meeting.peserta"
                            :disabled="
                                meeting.notulen === '' ||
                                meeting.moderator === '' ||
                                meeting.pimpinan === '' ||
                                meeting.notulen == null ||
                                meeting.moderator == null ||
                                meeting.pimpinan == null
                            "
                        />
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
                        <label for="">Alasan Perubahan Meeting</label>
                        <textarea
                            class="form-control"
                            v-model="meeting.alasan"
                        ></textarea>
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