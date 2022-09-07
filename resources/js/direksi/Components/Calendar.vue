<script>
    import '@fullcalendar/core/vdom' // solves problem with Vite
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import axios from 'axios'

    export default {
        name: 'Calendar',
        components: {
            FullCalendar
        },
        
        data() {
            return {
                calendarOptions: {
                    plugins: [dayGridPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    weekends: false,
                    locale: 'id',
                    headerToolbar: {
                    end: "",
                    },
                    events: [],
                }
            }
        },

        methods: {
            convertJadwal(jadwal) {
                return jadwal.length == 0
                    ? []
                    : jadwal.map((item) => ({
                        id: item.id,
                        title: `${item.produk.produk.nama} ${item.produk.nama}`,
                        start: item.tanggal_mulai,
                        end: item.tanggal_selesai,
                        backgroundColor: item.warna,
                        borderColor: item.warna,
                    }));
                },
            event() {
                axios.post("/api/prd/plan-cal").then(response => {
                    this.calendarOptions.events = this.convertJadwal(response.data);
                })
            },
            checkCalendar(){
                if(this.calendarOptions.events.length == 0){
                    console.log('kosong');
                }else{
                    this.$refs.calendar.getApi().next()
                }
            }
        },

        mounted() {
            this.event();
        },
        beforeUpdate() {
            this.checkCalendar();
        },
    }

</script>

<template>
    <div v-if="calendarOptions.events.length == 0">
        Belum ada Perencanaan Perakitan
    </div>
    <div v-else>
        <FullCalendar ref="calendar" :options="calendarOptions" />
    </div>
</template>

<style>
    .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

</style>
