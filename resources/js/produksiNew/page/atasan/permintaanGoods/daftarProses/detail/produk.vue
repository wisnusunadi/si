<script>
import edit from '../edit.vue';
export default {
    components: { edit },
    props: ['detail'],
    data() {
        return {
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                }
            ],
            search: '',
            showModal: false,
        }
    },
    methods: {
        statusEdit({ status }) {
            if (status === 'permintaan_ditolak_atasan' || status === 'permintaan_gagal_diacc' || status === 'permintaan_ditolak_gudang') {
                return true;
            } else {
                return false;
            }
        },
        openEdit() {
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
    <div class="card">
        <edit v-if="showModal" @close="showModal = false" :detail="detail" />
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                </div>
            </div>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <button class="btn btn-outline-warning btn-sm" v-if="statusEdit(detail.headers)" @click="openEdit">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm"
                        v-if="detail.headers.status != 'batal' && detail.headers.status != 'menunggu_persetujuan_atasan'"
                        @click=" batalPinjam">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button class="btn btn-outline-success btn-sm" @click="setuju(detail.headers.id)"
                        v-if="detail.headers.status == 'menunggu_persetujuan_atasan'">
                        <i class="fas fa-check"></i>
                        Setuju
                    </button>
                    <button class="btn btn-outline-danger btn-sm" @click="tolak(detail.headers.id)"
                        v-if="detail.headers.status == 'menunggu_persetujuan_atasan'">
                        <i class="fas fa-ban"></i>
                        Tolak
                    </button>
                </div>
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="headers" :items="detail.produk" :search="search" />
        </div>
    </div>
</template>