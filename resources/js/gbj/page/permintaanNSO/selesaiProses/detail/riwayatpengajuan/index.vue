<script>
import tolak from './tolak.vue'
export default {
    components: {
        tolak
    },
    props: ['pengajuan'],
    data() {
        return {
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'No. Pengajuan',
                    value: 'no_perubahan'
                },
                {
                    text: 'Versi Pengajuan',
                    value: 'versi'
                },
                {
                    text: 'Umpan Balik',
                    value: 'feedback'
                },
                {
                    text: 'Tanggal Pengajuan',
                    value: 'tgl_pengajuan'
                },
                {
                    text: 'Hasil',
                    value: 'hasil'
                },
                {
                    text: 'Alasan Ditolak',
                    value: 'alasan'
                }
            ],
            search: '',
            showModal: false,
            detailSelected: null
        }
    },
    methods: {
        terima() {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah diterima tidak dapat diubah lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        'Diterima!',
                        'Data berhasil diterima',
                        'success'
                    )
                }
            })
        },
        tolakPengajuan(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalAlasan').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <tolak v-if="showModal" :detail="detailSelected" @close="showModal = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="pengajuan" :search="search">
                    <template #item.feedback="{ item }">
                        <!-- make list -->
                        <ul>
                            <li v-for="feedback in item.feedback" :key="feedback">
                                {{ feedback }}
                            </li>
                        </ul>
                    </template>
                    <template #item.tgl_pengajuan="{ item }">
                        <div>
                            {{ dateFormat(item.tgl_pengajuan) }}
                        </div>
                    </template>
                    <template #item.hasil="{ item }">
                        <div v-if="!item.hasil">
                            <button class="btn btn-sm btn-outline-success" @click="terima">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" @click="tolakPengajuan(item)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>