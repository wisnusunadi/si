<script>
import detailComponents from './detail.vue'
import status from '../../../components/status.vue'
import modalAlasanTolak from './modalAlasanTolak.vue'
export default {
    components: {
        detailComponents,
        status,
        modalAlasanTolak
    },
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Permintaan',
                    value: 'no_permintaan'
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_referensi'
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tgl_permintaan'
                },
                {
                    text: 'Nama',
                    value: 'nama'
                },
                {
                    text: 'Bagian',
                    value: 'bagian'
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan_permintaan'
                },
                {
                    text: 'Tanggal Kebutuhan',
                    value: 'tgl_kebutuhan'
                },
                {
                    text: 'Durasi',
                    value: 'durasi'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            items: [
                {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '21 Agustus 2021',
                    tgl_kebutuhan: '22 Agustus 2021',
                    nama: 'Bagus',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Lorem',
                    durasi: '1 Hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080002',
                    no_referensi: 'SO-2021080002',
                    tgl_permintaan: '22 Agustus 2021',
                    tgl_kebutuhan: '23 Agustus 2021',
                    nama: 'Rudi',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Ipsum',
                    durasi: '2 Hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080003',
                    no_referensi: 'SO-2021080003',
                    tgl_permintaan: '23 Agustus 2021',
                    tgl_kebutuhan: '24 Agustus 2021',
                    nama: 'Yudi',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Dolor',
                    durasi: '3 Hari',
                    jenis: 'peminjaman',
                },
                {
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '24 Agustus 2021',
                    tgl_kebutuhan: '25 Agustus 2021',
                    nama: 'Susi',
                    bagian: 'Produksi',
                    tujuan_permintaan: 'Sit Amet',
                    durasi: null,
                    jenis: 'permintaan',
                },
            ],
            detailSelected: {},
            showModal: false,
            showModalTolak: false
        }
    },
    methods: {
        // setuju data
        setuju(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah disetujui tidak dapat diubah!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setuju!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Disetujui!',
                        'Data berhasil disetujui.',
                        'success'
                    )
                    $('.modalDetail').modal('hide')
                    this.$nextTick(() => {
                        this.showModal = false
                    })
                }
            })
        },
        // tolak data
        tolak(id) {
            this.showModalTolak = true
            this.$nextTick(() => {
                if (this.showModal) {
                    $('.modalDetail').modal('hide')
                    this.$nextTick(() => {
                        $('.modalTolak').modal('show')
                    })
                } else {
                    $('.modalTolak').modal('show')
                }
            })
        },
        // close modal tolak
        closeModalTolak() {
            $('.modalTolak').modal('hide');
            if (this.showModal) {
                this.$nextTick(() => {
                    this.showModalTolak = false
                    $('.modalDetail').modal('show')
                });
            } else {
                this.$nextTick(() => {
                    this.showModalTolak = false
                });
            }
        },
        // detail data
        detail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <modalAlasanTolak v-if="showModalTolak" @close="closeModalTolak" />
        <detailComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" @setuju="setuju"
            @tolak="tolak" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_permintaan="{ item }">
                <div>
                    {{ item.tgl_permintaan }}
                    <status :status="item.jenis" />
                </div>
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="btn btn-sm btn-outline-success" @click="setuju(item.id)">
                        <i class="fas fa-check"></i>
                        Setuju
                    </button>
                    <button class="btn btn-sm btn-outline-danger" @click="tolak(item.id)">
                        <i class="fas fa-times"></i>
                        Tolak
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>