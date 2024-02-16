<script>
import axios from 'axios'
import moment from 'moment'
export default {
    data() {
        return {
            headers: [
                {
                    text: 'Periode',
                    value: 'periode'
                },
                {
                    text: 'Permintaan Durasi Buka',
                    value: 'durasi_buka'
                },
                {
                    text: 'Alasan',
                    value: 'alasan'
                },
                {
                    text: 'Tanggal Pengajuan',
                    value: 'tanggal_pengajuan'
                },
                {
                    text: 'Pemohon',
                    value: 'pemohon'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [],
            search: '',
        }
    },
    methods: {
        terima(item) {
            this.$swal({
                text: `Yakin ingin menerima permintaan periode ${item.periode}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await axios.post(`/api/master/buka_periode/update/${item.id}`, {
                            status: 'terima'
                        })
                        this.$swal({
                            text: `Permintaan periode ${item.periode} berhasil diterima!`,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                        })

                        this.getData()
                    } catch (error) {
                        console.log(error)
                        swal.fire({
                            title: 'Gagal!',
                            text: `${error.response.data.message}`,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            })
        },
        tolak(item) {
            this.$swal({
                text: `Yakin ingin menolak permintaan periode ${item.periode}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await axios.post(`/api/master/buka_periode/update/${item.id}`, {
                            status: 'ditolak'
                        })
                        this.$swal({
                            text: `Permintaan periode ${item.periode} berhasil ditolak!`,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                        })
                        this.getData()
                    } catch (error) {
                        console.log(error)
                        swal.fire({
                            title: 'Gagal!',
                            text: `${error.response.data.message}`,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            })
        },
        async getData() {
            try {
                const { data } = await axios.get('/api/master/buka_periode/show').then(res => res.data)
                this.items = data.map(item => ({
                    tanggal_pengajuan: this.dateFormat(item.tgl_pengajuan),
                    ...item
                }))
            } catch (error) {
                console.log(error)
            }
        },
        statusBadge(status) {
            if (status == 'pengajuan') {
                return {
                    badge: 'primary',
                    text: 'white'
                }
            } else if (status == 'ditolak') {
                return {
                    badge: 'red',
                    text: 'white'
                }
            } else if (status == 'terima') {
                return {
                    badge: 'green',
                    text: 'white'
                }
            } else if (status == 'selesai') {
                return {
                    badge: '',
                    text: 'black'
                }
            }
        },
        tutup(item) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, saya yakin!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        this.$swal(
                            'Berhasil!',
                            'Periode penjualan telah diselesaikan',
                            'success'
                        )
                        await axios.get(`/api/master/buka_periode/selesai/${item.id}`, {
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('lokal_token')
                            }
                        })
                        this.getData()
                    } catch (error) {
                        console.log(error)
                        swal.fire(
                            'Gagal!',
                            `${error.response.data.message}`,
                            'error'
                        )
                    }
                }
            })
        },
        cekTglTutup(tglTutup) {
            const tglSekarang = new Date()
            const tglTutupDate = new Date(tglTutup)
            if (tglSekarang > tglTutupDate) {
                return {
                    text: `Lewat selama ${moment(tglTutupDate).lang('id').fromNow()}`,
                    color: 'red--text'

                }
            } else {
                return {
                    text: `Kurang ${moment(tglTutupDate).lang('id').fromNow()}`,
                    color: 'black--text'
                }
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <v-app>
        <v-main>
            <v-container>
                <div class="d-flex">
                    <v-card class="ml-5 mr-auto" flat>
                        <v-text-field v-model="search" placeholder="Cari Periode"></v-text-field>
                    </v-card>
                </div>
                <v-skeleton-loader v-if="$store.state.loading" class="mx-auto" type="table" />
                <v-data-table v-else :headers="headers" :items="items" :search="search">
                    <template #item.tanggal_pengajuan="{ item }">
                        <div>
                            <v-list-item two-line>
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.tanggal_pengajuan }}</v-list-item-title>
                                    <v-list-item-subtitle v-if="item.status == 'terima'">
                                        <span :class="cekTglTutup(item.tgl_tutup).color">{{ cekTglTutup(item.tgl_tutup).text
                                        }}</span>
                                    </v-list-item-subtitle>
                                    <v-list-item-subtitle>
                                        <v-chip :color="statusBadge(item.status)?.badge"
                                            :text-color="statusBadge(item.status)?.text" small>{{
                                                item.status }}</v-chip>
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div v-if="item.status == 'pengajuan'">
                            <v-btn outlined small color="primary" @click="terima(item)">
                                <v-icon>
                                    mdi-check
                                </v-icon>
                                Terima
                            </v-btn>
                            <v-btn outlined small color="error" @click="tolak(item)">
                                <v-icon>
                                    mdi-close
                                </v-icon>
                                Tolak
                            </v-btn>
                        </div>
                        <v-btn outlined small color="green" @click="tutup(item)" v-if="item.status == 'terima'">
                            <v-icon>
                                mdi-check
                            </v-icon>
                            Selesai
                        </v-btn>
                    </template>
                </v-data-table>
            </v-container>
        </v-main>
    </v-app>
</template>