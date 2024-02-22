<script>
export default {
    props: ['data'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'Nomor BPPB',
                    value: 'bppb'
                },
                {
                    text: 'Produk',
                    value: 'produk',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Progress',
                    value: 'progress',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                },
            ],
            years: new Date().getFullYear(),
        }
    },
    methods: {
        clickDetail(detail) {
            this.$router.push({
                name: 'barangMasukDetail',
                params: {
                    id: detail.id
                }
            })
        },
        refresh() {
            this.$emit('refresh')
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
            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="data" :search="search">
            <template #item.jumlah="{ item }">
                <div>
                    {{ item.jumlah }} Unit
                    <br><span class="badge badge-dark">
                        Terisi: {{ item.terisi }} Unit
                    </span>
                </div>
            </template>
            <template #item.progress="{ item }">
                <div>
                    <!-- baru -->
                    <span class="badge badge-success">Lolos: {{ item.lolos }} Unit {{ item.persentase_lolos }}%</span> <br>
                    <span class="badge badge-danger">Tidak Lolos: {{ item.tidak_lolos }} Unit {{ item.persentase_tidak_lolos
                    }}%</span>
                </div>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-outline-info btn-sm" @click="clickDetail(item)">
                    <i class="fas fa-flask"></i>
                    Pengujian
                </button>
            </template>
        </data-table>
    </div>
</template>