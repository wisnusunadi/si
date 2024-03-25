<script>
import cetakSJ from './cetakSJ.vue';
export default {
    props: ['dalam'],
    components: {
        cetakSJ
    },
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nomor SO',
                    value: 'so'
                },
                {
                    text: 'Nomor PO',
                    value: 'no_po'
                },
                {
                    text: 'Batas Pengiriman',
                    value: 'tgl_kontrak'
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
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            showModal: false,
            detailSelected: {},
            pengiriman: [
                {
                    text: 'Belum Dikirim',
                    value: 'belum_kirim'
                },
                {
                    text: 'Sebagian Dikirim',
                    value: 'sebagian_kirim'
                }
            ]
        }
    },
    methods: {
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Batas Kontrak Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
        showDetailSelected(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalCetak').modal('show');
            });
        },
        filterByYear(year) {
            this.$store.dispatch('setYears', year);
            this.$emit('refresh');
        },
        filter(status) {
            this.$emit('filter', status);
        }
    },
    computed: {
        years5BeforeNow() {
            const years = [];
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'));
            }
            return years;
        }
    }
}
</script>
<template>
    <div>
        <cetakSJ v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="filter">
                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter Tahun
                    </button>
                    <form id="filter">
                        <div class="dropdown-menu" style="">
                            <div class="px-3 py-3">
                                <div class="form-group">

                                    <div class="form-check" v-for="year in years5BeforeNow" :key="year">
                                        <input class="form-check-input" type="radio" :value="year"
                                            @click="filterByYear(year)" :checked="$store.state.years == year"
                                            :id="'defaultCheck' + year" />
                                        <label class="form-check-label" :for="'defaultCheck' + year">
                                            {{ year }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_penjualan">Pengiriman</label>
                                </div>
                                <div class="form-group" v-for="(item, key) in pengiriman" :key="key">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :value="item.value"
                                        @click="filter(item.value)"
                                            :id="`status${key}`">
                                        <label class="form-check-label" :for="`status${key}`">
                                            {{ item.text }}
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
        <data-table :headers="headers" :items="dalam" :search="search" v-if="!$store.state.loading">
            <template #item.tgl_kontrak="{ item }">
                <div v-if="item.tgl_kontrak">
                    <div :class="calculateDateFromNow(item.tgl_kontrak).color">{{
            dateFormat(item.tgl_kontrak) }}</div>
                    <small :class="calculateDateFromNow(item.tgl_kontrak).color">
                        <i :class="calculateDateFromNow(item.tgl_kontrak).icon"></i>
                        {{ calculateDateFromNow(item.tgl_kontrak).text }}
                    </small>
                </div>
                <div v-else></div>
            </template>
            <template #item.aksi="{ item }">
                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                    aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                    <button class="dropdown-item cetaksj" type="button" @click="showDetailSelected(item)">
                        <i class="fas fa-print"></i>
                        Cetak Surat Jalan
                    </button>
                </div>
            </template>
        </data-table>
        <div v-else class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>