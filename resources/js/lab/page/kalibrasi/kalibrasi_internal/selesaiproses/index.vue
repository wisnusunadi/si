<script>
import produk from './produk.vue'
export default {
    props: ['selesaiProses'],
    components: {
        produk
    },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Order',
                    value: 'order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'jenis_pemilik'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            detailSelected: null,
            modal: false,
        }
    },
    methods: {
        changeYears(year) {
            this.$store.dispatch('setYears', year);
            this.$emit('changeYears');
        },
        detail(item) {
            this.detailSelected = item;
            this.modal = true;
            this.$nextTick(() => {
                $('.modalProduk').modal('show');
            })
        },
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
    <div class="card">
        <div class="card-body">
            <produk v-if="modal" @closeModal="modal = false" :produk="detailSelected" />
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <span class="float-left filter">
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
                                                @change="changeYears(year)" :id="`exampleRadios${year}`" :value="year"
                                                :checked="year ==
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
            <data-table :headers="headers" :items="selesaiProses" :search="search" v-if="!$store.state.loading">
                <template #item.aksi="{ item }">
                    <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </template>
            </data-table>
            <div v-else class="d-flex justify-content-center align-items-center">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>