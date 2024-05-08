<script>
import status from '../../../../components/status.vue';
import tambah from './tambah.vue';
import edit from './edit.vue';
import detail from './detail.vue';
import alasan from './alasan.vue';
export default {
    components: { status, tambah, edit, detail, alasan },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'Nomor Permintaan',
                    value: 'no_permintaan',
                },
                {
                    text: 'Nomor Referensi',
                    value: 'no_referensi',
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tgl_permintaan',
                },
                {
                    text: 'Tujuan Permintaan',
                    value: 'tujuan_permintaan',
                },
                {
                    text: 'Tanggal Kebutuhan',
                    value: 'tgl_kebutuhan',
                },
                {
                    text: 'Durasi',
                    value: 'durasi',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            items: [
                {
                    id: 4,
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '2021-08-04',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-04',
                    status: 'menunggu_persetujuan',
                    durasi: null,
                    jenis: 'permintaan',
                },
                {
                    id: 4,
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '2021-08-04',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-04',
                    status: 'menunggu_persetujuan_atasan',
                    durasi: null,
                    jenis: 'permintaan',
                },
                {
                    id: 5,
                    no_permintaan: 'NSO-2021080005',
                    no_referensi: 'SO-2021080005',
                    tgl_permintaan: '2021-08-05',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-05',
                    status: 'permintaan_gagal_diacc',
                    durasi: '3 hari',
                    jenis: 'peminjaman',
                    ket: 'Lorem Ipsum',
                },
                {
                    id: 6,
                    no_permintaan: 'NSO-2021080006',
                    no_referensi: 'SO-2021080006',
                    tgl_permintaan: '2021-08-06',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-06',
                    status: 'permintaan_ditolak_gudang',
                    durasi: '3 hari',
                    jenis: 'permintaan',
                    ket: 'Lorem Ipsum',
                }
            ],
            showModal: false,
            detailSelected: {},
        }
    },
    methods: {
        openTambah() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalTambah').modal('show');
            });
        },
        openEdit(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalEdit').modal('show');
            });
        },
        openDetail(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalDetail').modal('show');
            });
        },  
        batalPinjam(id) {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah dibatalkan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Keluar'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire('Berhasil', 'Data berhasil dibatalkan', 'success');
                    // change status batal
                    const index = this.items.findIndex(item => item.id === id);
                    this.items[index].status = 'batal';
                }
            });
        },
        statusEdit({status}) {
            if (status === 'permintaan_ditolak_atasan' || status === 'permintaan_gagal_diacc' || status === 'permintaan_ditolak_gudang') {
                return true;
            } else {
                return false;
            }
        },
        setuju(id) {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah disetujui tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Keluar'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire('Berhasil', 'Data berhasil disetujui', 'success');
                }
            });
        },
        tolak(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalAlasan').modal('show');
            });
        }
    },
}
</script>
<template>
    <div>
        <tambah v-if="showModal" @close="showModal = false" />
        <edit v-if="showModal" @close="showModal = false" :item="detailSelected" />
        <detail v-if="showModal" @close="showModal = false" :item="detailSelected" />
        <alasan v-if="showModal" @close="showModal = false" :item="detailSelected" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.tgl_permintaan="{ item }">
                <div>
                    {{ dateFormat(item.tgl_permintaan) }} <br>
                    <status :status="item.jenis" />
                </div>
            </template>
            <template #item.tgl_kebutuhan="{ item }">
                <div>
                    {{ dateFormat(item.tgl_kebutuhan) }} <br>
                    <status :status="item.status" />
                </div>
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-sm btn-outline-primary" @click="openDetail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="btn btn-sm btn-outline-success" v-if="item.status == 'menunggu_persetujuan_atasan'"
                        @click="setuju(item)">
                        <i class="fas fa-check"></i>
                        Setuju
                    </button>
                    <button class="btn btn-sm btn-outline-danger" v-if="item.status == 'menunggu_persetujuan_atasan'"
                        @click="tolak(item)">
                        <i class="fas fa-ban"></i>
                        Tolak
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>