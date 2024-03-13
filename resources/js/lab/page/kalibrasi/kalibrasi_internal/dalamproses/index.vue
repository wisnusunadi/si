<script>
import loading from '../../../../components/loading.vue';
export default {
    components: {
        loading,
    },
    props: ['dataTable'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'pemilik_sertif'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ]
        }
    },
    methods: {
        detail(id) {
            this.$router.push({ name: 'detail-kalibrasi-internal', params: { id, jenis: 'dalamproses' } });
        }
    },
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        </div>
                    </div>
                </div>
                <data-table :headers="headers" :items="dataTable" :search="search" v-if="!$store.state.loading">
                    <template #item.status="{ item }">
                        <loading :persentase="item.status" />
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn-sm btn btn-outline-primary" @click="detail(item.id)">
                                <i class="fa fa-eye"></i>
                                Detail
                            </button>
                        </div>
                    </template>
                </data-table>
                <div class="d-flex justify-content-center" v-else>
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
