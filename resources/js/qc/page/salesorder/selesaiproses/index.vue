<script>
export default {
    props: ['selesai'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'No SO',
                    value: 'so'
                },
                {
                    text: 'No PO',
                    value: 'no_po'
                },

                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Keterangan',
                    value: 'keterangan'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            status: [
                {
                    text: 'E-Catalogue',
                    value: 'ekatalog'
                },
                {
                    text: 'SPA',
                    value: 'spa'
                },
                {
                    text: 'SPB',
                    value: 'spb'
                }
            ]
        }
    },
    methods: {
        cetak_sppb(id) {
            window.open(`/penjualan/penjualan/cetak_surat_perintah/${id}`, '_blank');
        },
        filter(status) {
            this.$emit('filter', status);
        },
        detail(item) {
            window.location.href = `/qc/so/detail/${item.id}/${item.jenis}`;
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="filter">
                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter Jenis Penjualan
                    </button>
                    <form id="filter">
                        <div class="dropdown-menu" style="">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <div class="form-check" v-for="(status, key) in status" :key="key">
                                        <input class="form-check-input" type="checkbox" :value="status.value"
                                            :id="`status${key}`" @click="filter(status.value)">
                                        <label class="form-check-label" for="defaultCheck1">
                                            {{ status.text }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="selesai" :search="search" v-if="!$store.state.loading">
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="btn btn-outline-primary btn-sm" @click="cetak_sppb(item.id)"
                        v-if="item.no_po != null && item.tgl_po != null">
                        <i class="fas fa-print"></i>
                        SPPB
                    </button>
                </div>
            </template>
        </data-table>
        <div v-else>
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>