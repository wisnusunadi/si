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
                    value: 'ket'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: ''
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="ekatalog"
                                            id="defaultCheck1" name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck1">
                                            E-Catalogue
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2"
                                            name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck2">
                                            SPA
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="spb" id="defaultCheck3"
                                            name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck3">
                                            SPB
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
        <data-table :headers="headers" :items="selesai" :search="search">
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-print"></i>
                        SPPB
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>