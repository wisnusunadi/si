<script>
export default {
    props: ['noseri'],
    data() {
        return {
            headers: [
                {
                    text: 'No Order',
                    value: 'no_order'
                },
                {
                    text: 'Nama Pemilik',
                    value: 'nama_pemilik'
                },
                {
                    text: 'Nama Pemilik Sertifikat',
                    value: 'nama_pemilik_sert'
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tanggal'
                },
                {
                    text: 'Teknisi',
                    value: 'pemeriksa'
                },
                {
                    text: 'Nama Barang',
                    value: 'nama_alat'
                },
                {
                    text: 'Tipe',
                    value: 'type'
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'Hasil',
                    value: 'hasil'
                }
            ],
            search: '',
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
    methods: {
        changeYear(year) {
            this.$store.dispatch('setYears', year);
            this.$emit('changeYears');
        }
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
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>
        <data-table :headers="headers" :items="noseri" :search="search" />
    </div>
</template>