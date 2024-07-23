<script>
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Item from "./item.vue";
import Edit from '../../meeting/aksi/edit.vue'
import Terlaksana from '../../meeting/aksi/terlaksana.vue'
export default {
    components: {
        Header,
        HeaderDetail,
        Item,
        Edit,
        Terlaksana,
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
                    link: "/meeting/hr",
                },
                {
                    name: "Detail Meeting",
                    link: "/meeting/hr/detail",
                },
            ],
            meeting: [],
            itemMeetingSelected: [],
            selectedData: 0,
            showModalEdit: false,
            editData: {},   
            showModalTerlaksana: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        selectItem(item, idx) {
            this.selectedData = idx;
            this.itemMeetingSelected = JSON.parse(JSON.stringify(item));
        },
        async getDetail() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await this.$_get(
                    `/api/hr/meet/jadwal/${this.$route.params.id}`
                );
                this.meeting = data.riwayat;
                this.itemMeetingSelected = JSON.parse(
                    JSON.stringify(this.meeting[this.meeting.length - 1])
                );
                this.selectedData = this.meeting.length - 1;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        async openEdit() {
            try {
                const { data } = await this.$_get(
                    `/api/hr/meet/jadwal/show_id/${this.$route.params.id}`
                );
                this.editData = JSON.parse(JSON.stringify(data));
                this.$delete(this.editData, "ket_batal");
                this.$delete(this.editData, "status_app");
                this.$delete(this.editData, "tgl_app");
                this.showModalEdit = true;
                this.$nextTick(() => {
                    $(".modalMeetingEdit").modal("show");
                });
            } catch (error) {
                console.log(error);
            }
        },
        async openTerlaksana() {
            try {
                const { data } = await this.$_get(
                    `/api/hr/meet/jadwal/show_id/${this.$route.params.id}`
                );
                this.editData = JSON.parse(JSON.stringify(data));
                this.showModalTerlaksana = true;
                this.$nextTick(() => {
                    $(".modalterlaksana").modal("show");
                });
            } catch (error) {
                console.log(error);
            }
        },
        returnToTerlaksana() {
            const id = this.$route.params.id;
            this.$router.push({ name: "detail-meeting-terlaksana", params: { id }})
        }
    },
    created() {
        this.getDetail();
    },
    computed: {
        reversedMeeting() {
            return this.meeting.slice().reverse();
        },
    },
};
</script>
<template>
    <div>
        <Terlaksana :meeting="editData" v-if="showModalTerlaksana" @closeModal="showModalTerlaksana = false" @refresh="returnToTerlaksana" />
        <Edit :meeting="editData" v-if="showModalEdit" @closeModal="showModalEdit = false" @refresh="getDetail" />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li
                class="nav-item"
                role="presentation"
                v-for="(item, idx) in reversedMeeting"
                :key="idx"
            >
                <a
                    class="nav-link"
                    :id="'pills-' + (meeting.length - 1 - idx) + '-tab'"
                    :class="{ active: idx === 0 }"
                    data-toggle="pill"
                    :data-target="'#pills-' + (meeting.length - 1 - idx)"
                    type="button"
                    role="tab"
                    :aria-controls="'pills-' + (meeting.length - 1 - idx)"
                    @click="selectItem(item, meeting.length - 1 - idx)"
                >
                    Rencana Meeting {{ meeting.length - idx }}
                </a>
            </li>
        </ul>
        <HeaderDetail
            :meeting="itemMeetingSelected"
            :lengthMeet="meeting.length"
            :selectedIndex="selectedData"
            @openEdit="openEdit"
            @openTerlaksana="openTerlaksana"
        />
        <Item :meeting="itemMeetingSelected.peserta" />
    </div>
</template>