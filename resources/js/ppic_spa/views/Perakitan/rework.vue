<script>
import $ from 'jquery'
import axios from 'axios'
export default {
    data() {
        return {
            rework: []
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.commit("setIsLoading", true);
                const { data } = await axios.get('/api/prd/rw/proses')
                this.rework = data.map((item) => {
                    return {
                        ...item,
                        progress: this.progress(item)
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.commit("setIsLoading", false);
            }
        },
        progress(item) {
            return Math.floor((item.selesai / item.jumlah) * 100)
        }
    },
    mounted() {
        this.getData()
    },
    watch: {
        rework() {
            this.$nextTick(() => {
                $('#tableRework').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json"
                    }
                });
            })
        }
    }   
}
</script>
<template>
    <div class="content field columns is-desktop">
        <div class="column">
            <table class="table" id="tableRework">
                <thead>
                    <tr>
                        <th rowspan="2">No Urut</th>
                        <th rowspan="2">Tanggal Mulai</th>
                        <th rowspan="2">Tanggal Selesai</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="2" class="has-text-centered">Jumlah</th>
                        <th rowspan="2">Progress</th>
                    </tr>
                    <tr>
                        <th>Selesai</th>
                        <th>Belum Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in rework" :key="index">
                        <td>PRD-{{ item.urutan }}</td>
                        <td>{{ dateFormat(item.tgl_mulai) }}</td>
                        <td>{{ dateFormat(item.tgl_selesai) }}</td>
                        <td>{{ item.nama }}</td>
                        <td>{{ item.selesai }}</td>
                        <td>{{ item.belum }}</td>
                        <td>
                            <span>{{ item.progress }}%</span>
                            <progress class="progress is-primary" :value="item.progress" max="100"></progress>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style>
.tableRework {
    width: 100%;
}
</style>