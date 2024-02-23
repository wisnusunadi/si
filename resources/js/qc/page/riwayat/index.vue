<script>
import Header from '../../components/header.vue';
export default {
    components: {
        Header,
    },
    data() {
        return {
            title: 'Riwayat Pengujian',
            breadcumbs: [
                {
                    name: 'Dashboard',
                    link: '/',
                },
                {
                    name: 'Riwayat Pengujian',
                    link: '#',
                },
            ],
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'No BPPB',
                    value: 'bppb'
                },
                {
                    text: 'Produk',
                    value: 'produk',
                    sortable: false
                },
                {
                    text: 'Tanggal Uji',
                    value: 'tanggal',
                    sortable: false
                },
                {
                    text: 'Waktu Uji',
                    value: 'waktu_uji'
                },
                {
                    text: 'Hasil',
                    value: 'hasil',
                    sortable: false
                },
                {
                    text: 'Penguji',
                    value: 'penguji',
                    sortable: false
                },
            ],
            items: [
                {
                    bppb: 'BPPB-001',
                    produk: 'Produk 1',
                    noseri: 'TD123',
                    tgl_uji: '2024-02-13',
                    tanggal: '13 Februari 2024',
                    waktu_uji: '12:00',
                    hasil: 'ok',
                    penguji: "Lorem",
                },
                {
                    bppb: 'BPPB-002',
                    produk: 'Produk 2',
                    noseri: 'TD124',
                    tgl_uji: '2024-02-01',
                    tanggal: '01 Februari 2024',
                    waktu_uji: '12:00',
                    hasil: 'not_ok',
                    penguji: "Ipsum",
                }
            ],
            search: '',
            filterProduk: [],
            filterHasil: [],
            filterPenguji: [],
            tanggalUjiAwal: '',
            tanggalUjiAkhir: '',
        }
    },
    methods: {
        statusText(status) {
            switch (status) {
                case 'belum_diuji':
                    return {
                        text: 'Belum Diuji',
                        class: 'text-warning fa fa-question-circle'
                    }
                case 'ok':
                    return {
                        text: 'Lolos',
                        class: 'text-success fa fa-check-circle'
                    }
                case 'not_ok':
                    return {
                        text: 'Tidak Lolos',
                        class: 'text-danger fa fa-times-circle'
                    }
            }
        },
        clickFilterHasil(item) {
            if (this.filterHasil.includes(item)) {
                this.filterHasil = this.filterHasil.filter(i => i !== item);
            } else {
                this.filterHasil.push(item);
            }
        },
        clickFilterPenguji(item) {
            if (this.filterPenguji.includes(item)) {
                this.filterPenguji = this.filterPenguji.filter(i => i !== item);
            } else {
                this.filterPenguji.push(item);
            }
        }
    },
    computed: {
        getUniqueProduk() {
            return [...new Set(this.items.map(item => item.produk))];
        },
        getUniqueHasil() {
            return [...new Set(this.items.map(item => item.hasil))];
        },
        getUniquePenguji() {
            return [...new Set(this.items.map(item => item.penguji))];
        },
        filteredRiwayat() {
            let filtered = this.items;
            if (this.filterProduk.length > 0) {
                filtered = filtered.filter(item => this.filterProduk.includes(item.produk));
            }
            if (this.filterHasil.length > 0) {
                filtered = filtered.filter(item => this.filterHasil.includes(item.hasil));
            }
            if (this.filterPenguji.length > 0) {
                filtered = filtered.filter(item => this.filterPenguji.includes(item.penguji));
            }
            if (this.tanggalUjiAwal && this.tanggalUjiAkhir) {
                const startDate = new Date(this.tanggalUjiAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalUjiAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_uji);
                    return date >= startDate && date <= endDate;
                });
            } else if (this.tanggalUjiAwal) {
                const startDate = new Date(this.tanggalUjiAwal);
                startDate.setHours(0, 0, 0, 0);
                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_uji);
                    return date >= startDate;
                });
            } else if (this.tanggalUjiAkhir) {
                const endDate = new Date(this.tanggalUjiAkhir);
                endDate.setHours(23, 59, 59, 999);
                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_uji);
                    return date <= endDate;
                });
            }

            return filtered;

        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="filteredRiwayat" :search="search">
                    <template #item.hasil="{ item }">
                        <span :class="statusText(item.hasil).class"></span>
                    </template>

                    <template #header.produk>
                        <span class="text-bold pr-2">Nama Produk</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <label for="">Nama Produk</label>
                                            <v-select :options="getUniqueProduk" v-model="filterProduk" multiple></v-select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #header.tanggal>
                        <span class="text-bold pr-2">Tanggal Uji</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <label for="">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalUjiAwal">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalUjiAkhir">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #header.hasil>
                        <span class="text-bold pr-2">Hasil</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-check" v-for="status in getUniqueHasil" :key="status">
                                            <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                @click="clickFilterHasil(status)" :checked="filterHasil.includes(status)" />
                                            <label class="form-check-label" for="defaultCheck1">
                                                {{ statusText(status).text }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #header.penguji>
                        <span class="text-bold pr-2">Penguji</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div :class="getUniquePenguji.length > 5 ? 'scrollable' : ''">
                                            <div class="form-check" v-for="status in getUniquePenguji" :key="status">
                                                <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                    @click="clickFilterPenguji(status)"
                                                    :checked="filterPenguji.includes(status)" />
                                                <label class="form-check-label" for="defaultCheck1">
                                                    {{ status }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-x: scroll;
    height: 400px;
}
</style>