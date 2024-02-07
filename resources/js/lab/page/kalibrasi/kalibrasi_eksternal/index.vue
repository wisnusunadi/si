<script>
import loading from '../../../components/loading.vue';
import tambah from './tambah.vue';
export default {
    components: { loading, tambah },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order',
                },
                {
                    text: 'Nama Pemilik',
                    value: 'nama_pemilik',
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'nama_pemilik_sertifikat',
                },
                {
                    text: 'Customer',
                    value: 'customer',
                },
                {
                    text: 'Status',
                    value: 'status',
                    sortable: false,
                },
                {
                    text: 'Keterangan',
                    value: 'keterangan',
                    sortable: false,
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            items: [{
                id: 36,
                no_order: '123',
                nama_pemilik: '123',
                nama_pemilik_sertifikat: '123',
                customer: '123',
                status: '30',
                keterangan: 'Lorem ipsum dolor sit amet',
            }],
            showModalTambah: false,
        }
    },
    methods: {
        tambahKalibrasi() {
            this.showModalTambah = true
            this.$nextTick(() => {
                $('#modelId').modal('show')
            })
        },
        detail(id) {
            this.$router.push({ name: 'detail-kalibrasi-eksternal', params: { id } })
        }
    },
}
</script>
<template>
    <div class="card">
        <tambah v-if="showModalTambah" @closeModal="showModalTambah = false" />
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <button class="btn btn-primary" @click="tambahKalibrasi">
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>
                </div>
                <div class="p-2 bd-highlight">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                </div>
            </div>

            <data-table :headers="headers" :items="items" :search="search" v-if="!$store.state.loading">
                <template #item.status="{ item }">
                    <div>
                        <loading :persentase="item.status" />
                    </div>
                </template>
                <template #item.aksi="{ item }">
                    <div>
                        <button class="btn btn-outline-primary btn-sm" @click="detail(item.id)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </div>
                </template>
            </data-table>
            <div class="d-flex justify-content-center" v-else>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>