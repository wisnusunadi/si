<script>
import produk from './produk.vue'
export default {
    components: {
        produk
    },
    data() {
        return {
            headers: [
                {
                    text: 'No Order',
                    value: 'order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'nama'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'jenis_pemilik'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            modal: false,
            selectedProduk: null,
            search: '',
        }
    },
    props: ['dataRiwayat'],
    methods: {
        changeYear(year) {
            this.$store.dispatch('setYears', year);
            this.$emit('changeYears');
        },
        detailProduk(data) {
            this.modal = true;
            this.selectedProduk = data;
            this.$nextTick(() => {
                $('.modalRiwayatProduk').modal('show');
            })
        }
    },
    computed: {
        // get 5 years from now
        getYear() {
            let year = [];
            for (let i = 0; i < 2; i++) {
                year.push(moment().subtract(i, "years").format("YYYY"));
            }
            return year;
        },
    },
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="filter">
                    <button class="btn btn-sm btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter Tahun
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <div class="form-group form-check" v-for="year in getYear" :key="year">
                                        <input class="form-check-input" type="radio" v-model="$store.state.years"
                                            @change="changeYear(year)" :id="`exampleRadios${year}`" :value="year" :checked="year ==
                                                new Date().getFullYear()
                                                " />
                                        <label class="form-check-label" :for="`exampleRadios${year}`">
                                            {{ year }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari" />
            </div>
        </div>
        <produk v-if="modal" @close="modal = false" :headerSO="selectedProduk" />
        <data-table :headers="headers" :items="dataRiwayat" :search="search" v-if="!$store.state.loading">
            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-primary" @click="detailProduk(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </data-table>
        <div class="spinner-border spinner-border-sm" role="status" v-else>
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>