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
                    belum_transfer: 10,
                    persentase_belum_transfer: 10,
                    sudah_transfer: 90,
                    persentase_sudah_transfer: 90,
                },
                {
                    no: 2,
                    so: 'SO-2021-0001',
                    po: 'PO-2021-0001',
                    customer: 'PT. ABC',
                    belum_transfer: 0,
                    persentase_belum_transfer: 0,
                    sudah_transfer: 100,
                    persentase_sudah_transfer: 100,
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
                    <span class="badge badge-info">Belum Transfer: {{ item.belum_transfer }}
                        ({{
            item.persentase_belum_transfer }}%)</span> <br>
                    <span class="badge badge-warning">Sudah Transfer: {{ item.sudah_transfer }} ({{
            item.sudah_transfer
        }}%)</span>

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