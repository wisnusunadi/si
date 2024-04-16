<script>
import status from '../../../../components/status.vue';
import tambah from './tambah.vue';
import edit from './edit.vue';
export default {
    components: { status, tambah, edit },
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
                    id: 1,
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    tgl_permintaan: '2021-08-01',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-01',
                    status: 'batal',
                    durasi: '1 hari',
                    jenis: 'peminjaman',
                },
                {
                    id: 4,
                    no_permintaan: 'NSO-2021080004',
                    no_referensi: 'SO-2021080004',
                    tgl_permintaan: '2021-08-04',
                    tujuan_permintaan: 'Lorem Ipsum',
                    tgl_kebutuhan: '2021-08-04',
                    status: 'menunggu_persetujuan_gudang',
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
        tolak(id) {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah ditolak tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tolak!',
                cancelButtonText: 'Keluar'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire('Berhasil', 'Data berhasil ditolak', 'success');
                }
            });
        }
    },
}
</script>
<template>
    <div>
        <tambah v-if="showModal" @close="showModal = false" />
        <edit v-if="showModal" @close="showModal = false" :item="detailSelected" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="openTambah">
                    <i class="fas fa-plus"></i> Tambah
                </button>
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
                    <div data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"
                        class="dropdown-toggle"><i class="fas fa-ellipsis-v"></i></div>
                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                        <button class="dropdown-item" type="button"
                            @click="$router.push({ name: 'permintaanGoodsMgrDetail', params: { id: item.id, status: item.status } })">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                        <button class="dropdown-item" type="button" v-if="statusEdit(item)" @click="openEdit(item)">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="dropdown-item" type="button" v-if="item.status != 'batal'"
                            @click="batalPinjam(item.id)">
                            <i class="fas fa-times"></i>
                            Batal
                        </button>
                        <div v-if="item.status == 'menunggu_persetujuan_atasan'">
                            <button class="dropdown-item" type="button" @click="setuju(item.id)">
                                <i class="fas fa-check"></i>
                                Setuju
                            </button>
                            <button class="dropdown-item" type="button" @click="tolak(item.id)">
                                <i class="fas fa-ban"></i>
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </data-table>
    </div>
</template>