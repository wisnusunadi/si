<template>
    <div>

    <div class="table-responsive">
        <table class="table text-center table-bordered">
            <thead>
                <tr>
                        <th rowspan="2">Nama Produk</th>
                        <th rowspan="2">Jumlah</th>
                        <th :colspan="last_date">Tanggal</th>
                    </tr>
                    <tr>
                        <th v-for="i in Array.from(Array(last_date).keys())" :key="i">
                            {{ i + 1 }}
                        </th>
                    </tr>
            </thead>
            <tbody>
                <tr v-for="item in event" :key="item.id">
                    <td>{{ item.title }}</td>
                    <td>{{ item.jumlah }}</td>
                    <td v-for="i in Array.from(Array(last_date).keys())" :key="i" :class="{
                        background_yellow: isDate(item.start, item.end, i+1),
                        background_black: weekend_date.indexOf(i+1) !== -1
                    }"></td>
                </tr>
            </tbody>
        </table>

    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                last_date: 1,
                event: [],
                weekend_date: [],
            }
        },
        created() {
            let dateObj = new Date();
            let month = dateObj.getUTCMonth() + 1;
            let year = dateObj.getUTCFullYear();
            let last_date = new Date(year, month + 1, 0).getDate();
            this.last_date = last_date;

            dateObj = new Date(year, month, 1);
            console.log(dateObj);
            for (let i = 1; i <= this.last_date; i++) {
            dateObj.setDate(i);
                if (dateObj.getDay() == 6 || dateObj.getDay() == 0) this.weekend_date.push(i);
            }
            console.log("created");
            console.log(this.weekend_date);
        },
        methods: {
            isDate(start_date, end_date, i) {
                let start = new Date(start_date);
                let end = new Date(end_date);

                let start_number = start.getDate();
                let end_number = end.getDate();

                // handle end equal to last date
                if (start.getMonth() !== end.getMonth()) end_number = this.last_date;

                if (i >= start_number && i <= end_number) return true;
                return false;
            },

            convertJadwal(jadwal){
                return jadwal.length == 0
                    ? []
                    :
                jadwal.map((item) => ({
                    id: item.id,
                    title: `${item.produk.produk.nama} ${item.produk.nama}`,
                    start: item.tanggal_mulai,
                    end: item.tanggal_selesai,
                    backgroundColor: item.warna,
                    borderColor: item.warna,

                    produk_id: item.produk_id,
                    jumlah: item.jumlah
                }));
            },

            events() {
                axios.get("/api/ppic/data/perakitan/penyusunan").then(response => {
                    this.event = this.convertJadwal(response.data);
                })
            },
        },
        mounted() {
            this.events();
            console.log(this.weekend_date);
        }
    }

</script>

<style scoped>
.background_yellow {
  background: yellow;
}

.background_black {
  background: black;
}
</style>
