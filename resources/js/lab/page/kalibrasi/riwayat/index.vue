<script>
import produk from '../../transferlab/qc/produk.vue';
export default {
    props: ['produk'],
    components: {
        produk,
    },
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
                    text: 'No SO',
                    value: 'so'
                },
                {
                    text: 'Customer',
                    value: 'customer'
                },
                {
                    text: 'Tanggal Kalibrasi',
                    value: 'tgl_transfer'
                },
                {
                    text: 'Jenis Transaksi',
                    value: 'jenis_transaksi'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            modal: false,
            selectedProduk: null,
            years: new Date().getFullYear()
        }
    },
    methods: {
        async getData() {
            console.log('get data');
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
    <div class="card">
        <div class="card-body">
            <produk v-if="modal" :headerSO="selectedProduk" />
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
                                            <input class="form-check-input" type="radio" v-model="years" @change="getData()"
                                                :id="`exampleRadios${year}`" :value="year" :checked="year ==
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
            <data-table :headers="headers" :items="produk" :search="search">
                <template #item.aksi="{ item }">
                    <div>
                        <button class="btn btn-outline-primary" @click="detailProduk(item)">
                            <i class="fa fa-eye"></i>
                            Detail
                        </button>
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>