<script>
import produkComponents from './produk.vue'
export default {
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
                    value: 'po'
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
            items: [
                {
                    no: 1,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    sudah_transfer: 0,
                    total: 100,
                },
                {
                    no: 2,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    sudah_transfer: 20,
                    total: 100,
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
        kirim() {
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
                    this.$swal(
                        'Berhasil!',
                        'Data berhasil dikirim',
                        'success'
                    )
                }

            })
        },
        progressTransfer(item) {
            if (item.sudah_transfer == item.total) {
                return {
                    text: 'Sudah Transfer',
                    color: 'badge-success'
                }
            } else if (item.sudah_transfer == 0) {
                return {
                    text: 'Belum Transfer',
                    color: 'badge-danger'
                }
            } else {
                return {
                    text: 'Sudah Transfer Sebagian',
                    color: 'badge-warning'
                }
            }
        }
    },
}
</script>
<template>
    <div>
        <produkComponents :detail="detailSelected" v-if="showModal" @close="showModal = false" />
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
                <button class="btn btn-sm btn-outline-primary" @click="kirim">
                    <i class="fa fa-paper-plane"></i>
                    Kirim
                </button>
            </template>


        </data-table>
    </div>
</template>