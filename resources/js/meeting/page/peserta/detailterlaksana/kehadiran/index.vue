<script>
import itemSelected from "./itemSelected.vue";
import HeaderDetail from "./headerDetail.vue";
export default {
    components: {
        itemSelected,
        HeaderDetail,
    },
    props: ["meeting"],
    data() {
        return {
            itemMeetingSelected: [],
            selectedData: 0,
        };
    },
    methods: {
        selectItem(item, idx) {
            this.selectedData = idx;
            this.itemMeetingSelected = JSON.parse(JSON.stringify(item));
        },
    },
    created() {
        this.itemMeetingSelected = JSON.parse(
            JSON.stringify(this.meeting[this.meeting.length - 1])
        );
        this.selectedData = this.meeting.length - 1;
    },
    computed: {
        reversedMeeting() {
            return this.meeting.slice().reverse();
        },
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
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
            />
            <itemSelected :meeting="itemMeetingSelected.peserta" />
        </div>
    </div>
</template>