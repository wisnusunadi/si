<script>
import Status from "../../../../components/status.vue"
import moment from "moment";

export default {
    props: ["meeting", "lengthMeet", "selectedIndex"],
    components: {
        Status,
    },
    data() {
        return {
            itemPendukung: [],
        };
    },
    methods: {
        changeFormatDate(date) {
            return moment(date).lang("id").format("dddd, DD MMMM YYYY");
        },
    },
    computed: {
        showImageFail() {
            if (this.lengthMeet > 0) {
                if (this.lengthMeet - 1 == this.selectedIndex) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        },
    }
};
</script>
<template>
    <div>
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
                                    <b id="distributor">{{
                                        meeting.urutan
                                        }}</b>
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
                                        <small class="text-muted">Jadwal Meeting</small>
                                    </div>
                                </div>
                                <div class="margin">
                                    <b id="distributor">{{
                                        changeFormatDate(meeting.tanggal)
                                        }}</b>
                                </div>
                                <small class="text-muted">Waktu</small>
                                <div class="margin">
                                    <b id="distributor">
                                        {{ meeting.mulai }} -
                                        {{ meeting.selesai }} WIB</b>
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
                                        <b id="no_so">{{
                                            meeting.jumlah_peserta
                                            }}
                                            Peserta</b>
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
                                    <div v-if="!showImageFail">
                                        <small class="text-muted">Cetak Undangan</small>
                                    </div>
                                    <div v-if="!showImageFail">
                                        <b id="no_so">
                                            <a :href="'/pdfmeet/undangan/' +
                                                $route.params.id" target="_blank">
                                                <button class="btn btn-success btn-sm mr-2 mb-2">
                                                    <i class="fas fa-print"></i>
                                                    Undangan Meeting
                                                </button>
                                            </a>
                                        </b>
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
                                        <b id="no_so">{{
                                            meeting.deskripsi
                                            }}</b>
                                    </div>
                                </div>
                                <div v-if="meeting.alasan_perubahan_meeting">
                                    <small class="text-muted">Alasan Perubahan Meeting</small>
                                    <div class="margin">
                                        <b id="distributor">
                                            {{
                                            meeting.alasan_perubahan_meeting
                                            }}
                                        </b>
                                    </div>
                                </div>
                                <div v-if="meeting.alasan_pembatalan_meeting">
                                    <small class="text-muted">Alasan Pembatalan Meeting</small>
                                    <div class="margin">
                                        <b id="distributor">
                                            {{
                                            meeting.alasan_pembatalan_meeting
                                            }}
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 bd-highlight" v-if="showImageFail">
                                <img src="../../../../assets/images/fail.png" alt="" width="100px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>