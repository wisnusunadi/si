<script>
import Header from '../../components/header.vue';
import dalamProses from './dalamproses';
import selesaiProses from './selesaiproses';
export default {
    components: {
        Header,
        dalamProses,
        selesaiProses
    },
    data() {
        return {
            title: 'Barang Masuk',
            breadcumbs: [
                {
                    name: 'Dashboard',
                    link: '/',
                },
                {
                    name: 'Barang Masuk',
                    link: '#',
                },
            ],
            dataDalamProses: [
                {
                    id: 1,
                    bppb: 'BPPB-001',
                    produk: 'Produk 1',
                    jumlah: 500,
                    terisi: 80,
                    lolos: 40,
                    persentase_lolos: 50,
                    tidak_lolos: 20,
                    persentase_tidak_lolos: 25,
                }
            ],
            // data dianggap selesai jika sesuai dengan bppb atau produksi close
            dataSelesaiProses: [
                {
                    id: 1,
                    bppb: 'BPPB-001',
                    produk: 'Produk 1',
                    jumlah: 500,
                    terisi: 80,
                    lolos: 40,
                    persentase_lolos: 50,
                    tidak_lolos: 20,
                    persentase_tidak_lolos: 25,
                    tanggal_selesai_uji: '2024-02-01', // diambil terakhir pengujian
                    tanggal: '01 Februari 2024'
                }
            ],
            years: new Date().getFullYear(),
            searchDalamProses: '',
            searchSelesaiProses: '',
        }
    },
    methods: {
        async getData() {
            // try {
            //     this.$store.dispatch('setLoading', true);
            //     const { data } = await axios.get('/api/prd/kirim')
            //     this.dataDalamProses = data.map(item => {
            //         return {
            //             ...item,
            //             tgl_mulai: this.dateFormat(item.tanggal_mulai),
            //             tgl_selesai: this.dateFormat(item.tanggal_selesai),
            //             jumlah: `${item.jumlah} Unit`,
            //         }
            //     })
            // } catch (error) {
            //     console.log(error)
            // } finally {
            //     this.$store.dispatch('setLoading', false);
            // }
        }
    },
    created() {
        this.getData()
    },
    computed: {
        getYear() {
            let year = [];
            for (let i = 0; i < 2; i++) {
                year.push(moment().subtract(i, "years").format("YYYY"));
            }
            return year;
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Dalam Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Selesai Proses</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                                                        <input class="form-check-input" type="radio" v-model="years"
                                                            @change="getData()" :id="`exampleRadios${year}`" :value="year"
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
                            <div class="p-2 bd-highlight"><input type="text" class="form-control"
                                    v-model="searchDalamProses" placeholder="Cari...">
                            </div>
                        </div>
                        <dalamProses :data="dataDalamProses" @refresh="getData" :search="searchDalamProses" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
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
                                                        <input class="form-check-input" type="radio" v-model="years"
                                                            @change="getData()" :id="`exampleRadios${year}`" :value="year"
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
                            <div class="p-2 bd-highlight"><input type="text" class="form-control"
                                    v-model="searchSelesaiProses" placeholder="Cari...">
                            </div>
                        </div>
                        <selesaiProses :data="dataSelesaiProses" @refresh="getData" :search="searchSelesaiProses" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>