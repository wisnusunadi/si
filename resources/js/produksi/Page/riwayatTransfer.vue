<template>
    <v-app>
        <v-main>
            <v-container>
                <h2 class="display-1">Riwayat Transfer</h2>
                <v-row>
                    <v-col cols="12" md="12">
                        <v-data-table :headers="headers" :items="dateFilter">
                            <template v-slot:top>
                                <v-toolbar flat extended >
                                    <v-row>
                                        <v-col cols="12" md="4">
                                             <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                            transition="scale-transition" offset-y max-width="290px" min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="dateRangeText" clearable
                                                    label="Filter Tanggal" hint="YYYY-MM-DD format"
                                                    persistent-hint prepend-icon="mdi-calendar" :attrs="attrs" v-on="on"
                                                    @click:clear="date = null"></v-text-field>
                                            </template>
                                            <v-date-picker @input="menu1 = false" range v-model="date"></v-date-picker>
                                        </v-menu>
                                        </v-col>
                                    </v-row>
                                    <v-dialog v-model="dialog" max-width="1000px">
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{ nama_produk }}</span>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search" append-icon="mdi-magnify" label="Cari" single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-data-table :headers="headersdetail" :items="data_detail" :search="search"></v-data-table>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:[`item.aksi`]="{item}">
                                <v-icon small class="mr-2" @click="seeItem(item)">
                                    mdi-archive-eye-outline
                                </v-icon>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                search: null,
                dialog: false,
                menu1: false,
                dataRiwayat: [],
                date: null,
                nama_produk: null,
                data_detail: [],
                headers: [{
                        text: 'Tanggal Pengiriman',
                        value: 'day_kirim'
                    },
                    {
                        text: 'Waktu Pengiriman',
                        value: 'time_kirim'
                    },
                    {
                        text: 'Nomor BPPB',
                        value: 'nomor_bppb'
                    },
                    {
                        text: 'Produk',
                        value: 'produk'
                    },
                    {
                        text: 'Jumlah',
                        value: 'jml'
                    },
                    {
                        text: 'Aksi',
                        align: 'left',
                        sortable: false,
                        value: 'aksi'
                    }
                ],
                headersdetail: [{
                    text: 'No Seri',
                    value: 'no_seri'
                }],
            }
        },
        methods: {
            async loadData() {
                try {
                    await axios.post('/api/prd/history/pengiriman').then(res => {
                        this.dataRiwayat = res.data.data;
                    });
                    $('#myTable').DataTable();
                } catch (error) {
                    console.log(error);
                }
            },
            async seeItem(item) {
                let id = item.produk_id;
                let tf = item.day_kirim_filter + ' ' + item.time_kirim;
                this.data_detail = [];
                this.nama_produk = item.produk;
                try {
                    await axios.get('/api/prd/historySeri/' + id + '/' + tf).then(res => {
                        this.data_detail = res.data.data;
                    });
                    console.log("berhasil");
                } catch (error) {
                    console.log(error);
                }
                this.dialog = true;

            }
        },
        mounted() {
            this.loadData();
        },
        computed: {
            dateFilter() {
                if (this.date != null) {
                    let dataFilter = this.dataRiwayat.filter(item => {
                        return item.day_kirim_filter >= this.date[0] && item.day_kirim_filter <= this.date[1]
                    })
                    return dataFilter
                } else {
                    return this.dataRiwayat
                }
            },
            dateRangeText() {
                if (this.date) {
                    return this.date[0] + ' - ' + this.date[1];
                }
            }
        },
    }

</script>
