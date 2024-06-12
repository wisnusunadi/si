<script>
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Kehadiran from "./kehadiran";
import HasilNotulensi from "./notulen";
import HasilMeeting from "./hasilmeeting";
import DokumenPendukung from "./dokumenpendukung";
import axios from "axios";
import modalSelectLampiran from "../../meeting/detailterlaksana/modalSelectLampiran.vue";
export default {
    components: {
        Header,
        HeaderDetail,
        Kehadiran,
        HasilNotulensi,
        HasilMeeting,
        DokumenPendukung,
        modalSelectLampiran,
    },
    data() {
        return {
            title: "Detail Meeting",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "#",
                },
                {
                    name: "Meeting",
                    link: "/meeting/jadwal_meet",
                },
                {
                    name: "Detail Meeting",
                    link: "/meeting/jadwal_meet/detail",
                },
            ],
            meeting: null,
            tabs: "kehadiran",
            showModalCetak: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        groupDocuments(documents) {
            const groupedDocuments = {
                foto: [],
                video: [],
                rekaman: [],
                dokumen: [],
            };

            documents.forEach((doc) => {
                const ext = doc.nama.split(".").pop().toLowerCase();

                switch (ext) {
                    case "jpg":
                    case "jpeg":
                    case "png":
                        groupedDocuments.foto.push(doc);
                        break;
                    case "mp4":
                    case "avi":
                    case "mkv":
                        groupedDocuments.video.push(doc);
                        break;
                    case "mp3":
                    case "wav":
                        groupedDocuments.rekaman.push(doc);
                        break;
                    default:
                        groupedDocuments.dokumen.push(doc);
                        break;
                }
            });

            return groupedDocuments;
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(
                    `/api/hr/meet/jadwal_person/detail/${this.$route.params.id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "lokal_token"
                            )}`,
                        },
                    }
                );
                this.meeting = data;
                this.meeting.headers =
                    data.kehadiran.riwayat[data.kehadiran.riwayat.length - 1];
                this.meeting.dokumen_meet = [
                    {
                        jenis: "foto",
                        dokumen: this.groupDocuments(
                            data.kehadiran.dokumen_meet
                        ).foto,
                    },
                    {
                        jenis: "video",
                        dokumen: this.groupDocuments(
                            data.kehadiran.dokumen_meet
                        ).video,
                    },
                    {
                        jenis: "rekaman",
                        dokumen: this.groupDocuments(
                            data.kehadiran.dokumen_meet
                        ).rekaman,
                    },
                    {
                        jenis: "dokumen",
                        dokumen: this.groupDocuments(
                            data.kehadiran.dokumen_meet
                        ).dokumen,
                    },
                ];
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        cetakHasilMeet() {
            this.showModalCetak = true;
            this.$nextTick(() => {
                $(".modalSelectLampiran").modal("show");
            });
        },
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <modalSelectLampiran
            v-if="showModalCetak"
            :dokumen="meeting.dokumen_meet"
            @closeModal="showModalCetak = false"
        />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <!-- menampilkan data terbaru -->
        <div v-if="!$store.state.loading">
            <HeaderDetail
                :meeting="meeting.headers"
                @refresh="getData"
                @cetakHasilMeet="cetakHasilMeet"
            />
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="{ active: tabs === 'kehadiran' }"
                        @click="tabs = 'kehadiran'"
                        id="pills-kehadiran-tab"
                        data-toggle="pill"
                        data-target="#pills-kehadiran"
                        type="button"
                        role="tab"
                        aria-controls="pills-kehadiran"
                        :aria-selected="tabs === 'kehadiran'"
                    >
                        Kehadiran
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="{ active: tabs === 'hasil_notulensi' }"
                        @click="tabs = 'hasil_notulensi'"
                        id="pills-hasil-notulensi-tab"
                        data-toggle="pill"
                        data-target="#pills-hasil-notulensi"
                        type="button"
                        role="tab"
                        aria-controls="pills-hasil-notulensi"
                        :aria-selected="tabs === 'hasil_notulensi'"
                    >
                        Hasil Notulensi
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="{ active: tabs === 'hasil_meeting' }"
                        @click="tabs = 'hasil_meeting'"
                        id="pills-hasil-meeting-tab"
                        data-toggle="pill"
                        data-target="#pills-hasil-meeting"
                        type="button"
                        role="tab"
                        aria-controls="pills-hasil-meeting"
                        :aria-selected="tabs === 'hasil_meeting'"
                    >
                        Hasil Meeting
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="{ active: tabs === 'dokumen_pendukung' }"
                        @click="tabs = 'dokumen_pendukung'"
                        id="pills-dokumen-pendukung-tab"
                        data-toggle="pill"
                        data-target="#pills-dokumen-pendukung"
                        type="button"
                        role="tab"
                        :aria-selected="tabs === 'dokumen_pendukung'"
                        aria-controls="pills-dokumen-pendukung"
                    >
                        Dokumen Pendukung
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div
                    class="tab-pane fade"
                    :class="{ 'show active': tabs === 'kehadiran' }"
                    id="pills-kehadiran"
                    role="tabpanel"
                    aria-labelledby="pills-kehadiran-tab"
                >
                    <kehadiran :meeting="meeting.kehadiran.riwayat" />
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ 'show active': tabs === 'hasil_notulensi' }"
                    id="pills-hasil-notulensi"
                    role="tabpanel"
                    aria-labelledby="pills-hasil-notulensi-tab"
                >
                    <hasil-notulensi
                        :meeting="meeting.kehadiran.hasil_notulen"
                        :status="meeting.kehadiran.status"
                        @refresh="getData"
                    />
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ 'show active': tabs === 'hasil_meeting' }"
                    id="pills-hasil-meeting"
                    role="tabpanel"
                    aria-labelledby="pills-hasil-meeting-tab"
                >
                    <hasil-meeting
                        :meeting="meeting.kehadiran.hasil_meet"
                        :status="meeting.kehadiran.status"
                    />
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ 'show active': tabs === 'dokumen_pendukung' }"
                    id="pills-dokumen-pendukung"
                    role="tabpanel"
                    aria-labelledby="pills-dokumen-pendukung-tab"
                >
                    <dokumen-pendukung
                        :meeting="meeting.dokumen_meet"
                        :status="meeting.status"
                    />
                </div>
            </div>
        </div>
        <div v-else>
            <div
                class="d-flex justify-content-center align-items-center"
                style="height: 100vh"
            >
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>