<script>
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
            items: [
                {
                    periode: '2021',
                    permintaan_durasi_buka: '2 Hari',
                    alasan: 'Mengurangi beban kerja',
                    tanggal_pengajuan: '2021-01-01',
                    pemohon: 'Admin',
                }
            ],
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
                this.$swal('Berhasil', 'Permintaan periode berhasil diterima', 'success')
                this.items = this.items.filter(i => i.periode !== item.periode)
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
                this.$swal('Berhasil', 'Permintaan periode berhasil diterima', 'success')
                this.items = this.items.filter(i => i.periode !== item.periode)
            })
        }
    },
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