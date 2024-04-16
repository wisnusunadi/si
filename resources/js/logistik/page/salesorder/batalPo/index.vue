<script>
import produkComponents from './produk.vue'
import axios from 'axios'
export default {
    props: ['items'],
    components: {
        produkComponents
    },
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor SO',
                    value: 'so'
                },
                {
                    text: 'Nomor PO',
                    value: 'no_po'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Progress',
                    value: 'progress'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            detailSelected: {},
            search: '',
            showModal: false,
        }
    },
    methods: {

        showDetail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        kirim(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Data akan dikirim ke gudang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/penjualan/batal_po/log/kirim_semua', {
                        id
                    }).then((res) => {
                        this.$swal({
                            title: 'Berhasil',
                            text: 'Data berhasil dikirim',
                            icon: 'success',
                            timer: 2000
                        })
                        this.$emit('refresh')
                    }).catch((err) => {
                        this.$swal({
                            title: 'Gagal',
                            text: 'Data gagal dikirim',
                            icon: 'error',
                        })
                    })
                }
            })
        },
        progressTransfer(item) {
            if (item.jumlah_tf == 0) {
                return {
                    text: 'Belum Transfer',
                    color: 'badge-danger'
                }
            } else if (item.jumlah == item.jumlah_tf) {
                return {
                    text: 'Sudah Transfer',
                    color: 'badge-success'
                }
            } else {
                return {
                    text: 'Sudah Transfer Sebagian',
                    color: 'badge-warning'
                }
            }
        },
    },
}
</script>
<template>
    <div>
        <produkComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" @refresh="$emit('refresh')" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.progress="{ item }">
                <div>
                    <span :class="'badge ' + progressTransfer(item).color">{{ progressTransfer(item).text }}</span>
                </div>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-info" @click="showDetail(item)">
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="kirim(item.id)">
                    <i class="fa fa-paper-plane"></i>
                    Kirim
                </button>
            </template>


        </data-table>
    </div>
</template>