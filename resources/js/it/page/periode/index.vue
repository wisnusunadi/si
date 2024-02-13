<script>
import axios from 'axios'
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
                    value: 'permintaan_durasi_buka'
                },
                {
                    text: 'Alasan',
                    value: 'alasan'
                },
                {
                    text: 'Tanggal Pengajuan',
                    value: 'tgl_pengajuan'
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
                try {
                    await axios.post(`/api/master/buka_periode/update/${item.id}`, {
                        status: 'tolak'
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
                        text: 'Terjadi kesalahan saat menolak permintaan periode',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
        },
        async getData() {
            try {
                const { data } = await axios.get('/api/permintaan_pengajuan_periode')
                this.items = data
            } catch (error) {
                console.log(error)
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
                    <template #item.aksi="{ item }">
                        <div>
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
                    </template>
                </v-data-table>
            </v-container>
        </v-main>
    </v-app>
</template>