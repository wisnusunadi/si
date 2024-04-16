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
                    <button class="btn btn-outline-warning btn-sm" v-if="detail.headers.status != 'batal'"
                        @click="openEdit">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm" v-if="detail.headers.status != 'batal'"
                        @click="batalPinjam">
                        <i class="fas fa-times"></i>
                        Batal
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