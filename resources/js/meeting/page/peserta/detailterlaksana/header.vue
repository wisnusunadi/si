<script>
import Status from "../../../components/status.vue";
import axios from "axios";
export default {
    props: ["meeting"],
    components: {
        Status,
    },
    data() {
        return {
            showApprove: false,
        };
    },
    methods: {
        async getApproveMeetingStatus() {
            try {
                const { data } = await axios.get(
                    `/api/hr/meet/jadwal/checkApproval/${this.$route.params.id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "lokal_token"
                            )}`,
                        },
                    }
                );
                this.showApprove = data;
            } catch (error) {
                console.log(error);
            }
        },
        approve(id) {
            swal.fire({
                title: "Setujui?",
                text: "Apakah anda yakin ingin menyetujui data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(
                            "/api/hr/meet/jadwal/update/approve_setuju",
                            {
                                id,
                                notulen: this.meeting.hasil_notulen,
                            },
                            {
                                headers: {
                                    Authorization: `Bearer ${localStorage.getItem(
                                        "lokal_token"
                                    )}`,
                                },
                            }
                        )
                        .then((res) => {
                            swal.fire(
                                "Berhasil!",
                                "Data telah disetujui.",
                                "success"
                            );
                            this.$emit("refresh");
                        })
                        .catch((err) => {
                            swal.fire(
                                "Gagal!",
                                "Data gagal disetujui.",
                                "error"
                            );
                        });
                }
            });
        },
        reject(id) {
            swal.fire({
                title: "Tolak?",
                text: "Apakah anda yakin ingin menolak data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(
                            "/api/hr/meet/jadwal/update/approve_batal",
                            {
                                id,
                            },
                            {
                                headers: {
                                    Authorization: `Bearer ${localStorage.getItem(
                                        "lokal_token"
                                    )}`,
                                },
                            }
                        )
                        .then((res) => {
                            swal.fire(
                                "Berhasil!",
                                "Data telah ditolak.",
                                "success"
                            );
                            this.$emit("refresh");
                        })
                        .catch((err) => {
                            swal.fire("Gagal!", "Data gagal ditolak.", "error");
                        });
                }
            });
        },
    },
    mounted() {
        this.getApproveMeetingStatus();
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4>{{ meeting.judul }}</h4>
            <div class="row">
                <div class="col-lg-11 col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">No Meeting</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">{{ meeting.urutan }}</b>
                            </div>
                            <small class="text-muted">Notulen</small>
                            <div class="margin">
                                <b id="distributor">
                                    {{ meeting.notulen }}
                                </b>
                            </div>
                            <small class="text-muted">Moderator</small>
                            <div class="margin">
                                <b id="distributor">
                                    {{ meeting.moderator }}
                                </b>
                            </div>
                        </div>
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted"
                                        >Jadwal Meeting</small
                                    >
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">{{
                                    dateFormat(meeting.tgl_meeting)
                                }}</b>
                            </div>
                            <small class="text-muted">Waktu</small>
                            <div class="margin">
                                <b id="distributor">
                                    {{ meeting.mulai }} -
                                    {{ meeting.selesai }} WIB</b
                                >
                            </div>
                            <small class="text-muted">Pimpinan Rapat</small>
                            <div class="margin">
                                <b id="distributor">
                                    {{ meeting?.pimpinan }}
                                </b>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Lokasi</small>
                                </div>
                                <div>
                                    <b id="no_so">{{ meeting.lokasi }}</b>
                                </div>
                            </div>
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Jumlah</small>
                                </div>
                                <div>
                                    <b id="no_so"
                                        >{{
                                            meeting.peserta?.length
                                        }}
                                        Peserta</b
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Status</small>
                                </div>
                                <div>
                                    <b id="no_so">
                                        <Status :status="meeting.status" />
                                    </b>
                                </div>
                                <div>
                                    <small class="text-muted"
                                        >Cetak Laporan</small
                                    >
                                </div>
                                <div>
                                    <b id="no_so">
                                        <a
                                            @click="$emit('cetakHasilMeet')"
                                        >
                                            <button
                                                class="btn btn-success btn-sm mr-2 mb-2"
                                            >
                                                <i class="fas fa-print"></i>
                                                Laporan Meeting
                                            </button>
                                        </a>
                                    </b>
                                </div>
                                <div
                                    v-if="
                                        meeting.status ==
                                            'menunggu_approval_pimpinan' &&
                                        showApprove
                                    "
                                >
                                    <small class="text-muted"
                                        >Approve Meeting</small
                                    >
                                </div>
                                <div
                                    v-if="
                                        meeting.status ==
                                            'menunggu_approval_pimpinan' &&
                                        showApprove
                                    "
                                >
                                    <button
                                        class="btn btn-success btn-sm"
                                        @click="approve"
                                    >
                                        <i class="fas fa-check"></i>
                                        Setujui
                                    </button>
                                    <button
                                        class="btn btn-danger btn-sm"
                                        @click="reject"
                                    >
                                        <i class="fas fa-times"></i>
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex bd-highlight">
                        <div class="flex-grow-1 bd-highlight">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Deskripsi</small>
                                </div>
                                <div>
                                    <b id="no_so">{{ meeting.deskripsi }}</b>
                                </div>
                            </div>
                            <div v-if="meeting.alasan_perubahan_meeting">
                                <small class="text-muted"
                                    >Alasan Perubahan Meeting</small
                                >
                                <div class="margin">
                                    <div class="badge badge-danger">
                                        {{ meeting.alasan_perubahan_meeting }}
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