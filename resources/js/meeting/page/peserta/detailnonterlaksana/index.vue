<script>
import axios from 'axios';
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Item from "./item.vue";
export default {
    components: {
        Header,
        HeaderDetail,
        Item,
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
            const getKehadiran = (kehadiran) => {
                switch (kehadiran) {
                    case 1:
                        return 'hadir'
                    case 2:
                        return 'tidak_hadir'
                    default:
                        return null
                }
            }

            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(`/api/hr/meet/jadwal/${this.$route.params.id}`);
                this.meeting = data.riwayat.map((item) => {
                    return {
                        ...item,
                        peserta: item.peserta.map((peserta) => {
                            return {
                                ...peserta,
                                kehadiran: getKehadiran(peserta.status)
                            }
                        })
                    }
                });
                                this.itemMeetingSelected = JSON.parse(
                    JSON.stringify(this.meeting[this.meeting.length - 1])
                );
                this.selectedData = this.meeting.length - 1;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
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
        <HeaderDetail :meeting="itemMeetingSelected" :lengthMeet="meeting.length" :selectedIndex="selectedData" />
        <Item :meeting="itemMeetingSelected.peserta" />
    </div>
</template>