<script>
import moment from "moment";
export default {
    props: ['monthYears', 'dataTable'],
    methods: {
        isWeekend(date) {
            // check if the date is a Saturday or Sunday
            let dayOfWeek = moment(`${date}-${this.monthYears}`, "D-MMMM YYYY").format("dddd")
            return dayOfWeek === "Saturday" || dayOfWeek === "Sunday";
        },
        isInRange(date, data) {
            // check if the date is within the range of tanggal_mulai and tanggal_selesai
            let tanggalMulai = moment(data.tanggal_mulai, "YYYY-MM-DD");
            let tanggalSelesai = moment(data.tanggal_selesai, "YYYY-MM-DD");
            date = moment(date, "D-MM-YYYY");
            if (this.isWeekend(date)) {
                return false;
            }
            return date.isBetween(tanggalMulai, tanggalSelesai, null, '[]');
        },
        edit(id) {
            this.$emit('edit', id)
        },
        hapus(id) {
            this.$emit('hapus', id)
        }
    },
    computed: {
        getDatesMonthNow() {
            // convert monthYears November 2023 to 11-2023
            let monthYearNow = moment(this.monthYears, "MMMM YYYY").format("MM-YYYY");
            let dates = [];
            // get dates only like 1,2,3
            for (let i = 1; i <= moment(monthYearNow, "MM-YYYY").daysInMonth(); i++) {
                dates.push(i);
            }
            return dates;
        },
    }

}
</script>
<template>
    <div class="table-container">
        <table class="table is-bordered has-text-centered" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Aksi</th>
                    <th :colspan="getDatesMonthNow.length">Tanggal</th>
                </tr>
                <tr>
                    <th v-for="date in getDatesMonthNow" :key="date">{{ date }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>
                        <span>
                            <i class="fas fa-edit pointerHand" @click="edit(data.id)"></i>
                        </span>
                        &nbsp;
                        <span>
                            <i class="fas fa-trash pointerHand" @click="hapus(data.id)"></i>
                        </span>
                    </td>
                    <td v-for="date in getDatesMonthNow" :key="date"
                        :class="{ 'yellow-bg': isInRange(date, data), 'black-bg': isWeekend(date) }">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style scoped>
.pointerHand {
    cursor: pointer;
}

.black-bg {
    background-color: black;
}

.yellow-bg {
    background-color: yellow;
}
</style>
                        