<script>
export default {
    props: ['pengeluaran'],
    data() {
        return {
            searchProduk: '',
            headersProduk: [],
            searchNoSeri: '',
            headersNoSeri: [],
            noseri: [],
            pengeluaranDuplicate: JSON.parse(JSON.stringify(this.pengeluaran)),
            detailSelected: null,
        }
    },
    methods: {
        detailProduk(item) {
            this.detailSelected = item
            this.noseri = [
                {
                    no: 1,
                    id: 1,
                    noseri: 'NS-2021080001',
                    waktu_ambil: '2024-08-24 13:00:00',
                },
                {
                    no: 2,
                    id: 2,
                    noseri: 'NS-2021080002',
                    waktu_ambil: '2024-08-24 13:00:00',
                }
            ]
        },
        checkedOne(item) {
            const index = this.pengeluaranDuplicate.findIndex(x => x.id === this.detailSelected.id)
            this.pengeluaranDuplicate = this.pengeluaranDuplicate.map((x, i) => {
                if (i === index) {
                    return {
                        ...x,
                        noSeriSelected: x.noSeriSelected?.find((n) => n.id === item.id) ? x.noSeriSelected?.filter((n) => n.id !== item.id) : [...x.noSeriSelected, item]
                    }
                }
                return x
            })
        },
        checkAll() {
            const index = this.pengeluaranDuplicate.findIndex(x => x.id === this.detailSelected.id)
            this.pengeluaranDuplicate = this.pengeluaranDuplicate.map((x, i) => {
                if (i === index) {
                    return {
                        ...x,
                        noSeriSelected: this.noseri ? this.noseri : []
                    }
                }
                return x
            })
        },
        cekHeaderPengeluaran() {
            if (this.$route.params.selesai) {
                this.headersProduk = [
                    {
                        text: 'No.',
                        value: 'no'
                    },
                    {
                        text: 'Nama Produk',
                        value: 'nama',
                    },
                    {
                        text: 'Jumlah',
                        value: 'jumlah'
                    },
                    {
                        text: 'Waktu Ambil',
                        value: 'waktu_ambil'
                    },
                    {
                        text: 'Aksi',
                        value: 'aksi',
                    }
                ]

                this.headersNoSeri = [
                    {
                        text: 'No.',
                        value: 'no',
                    },
                    {
                        text: 'No. Seri',
                        value: 'noseri',
                    },
                    {
                        text: 'Waktu Diterima',
                        value: 'waktu_ambil',
                    }
                ]
            } else {
                this.headersProduk = [
                    {
                        text: 'No.',
                        value: 'no',
                    },
                    {
                        text: 'Nama Produk',
                        value: 'nama',
                    },
                    {
                        text: 'Jumlah',
                        value: 'jumlah'
                    },
                    {
                        text: 'Aksi',
                        value: 'aksi',
                    }
                ]

                this.headersNoSeri = [
                    {
                        text: 'No.',
                        value: 'no',
                        sortable: false
                    },
                    {
                        text: 'No. Seri',
                        value: 'noseri',
                    },
                ]
            }
        }
    },
    created() {
        this.cekHeaderPengeluaran()
    }
}
</script>
<template>
    <div class="row">
        <div :class="noseri.length > 0 ? 'col-8' : 'col-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                        </div>
                    </div>
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">

                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="pengeluaran" :search="searchProduk">
                        <template #item.waktu_ambil="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_ambil) }}
                            </div>
                        </template>
                        <template #item.aksi="{ item }">
                            <button class="btn btn-sm btn-outline-primary" @click="detailProduk(item)">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
        <div class="col-4" v-if="noseri.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchNoSeri">
                        </div>
                    </div>
                    <data-table :headers="headersNoSeri" :items="noseri" :search="searchNoSeri">
                        <template #header.no>
                            <input type="checkbox" v-if="!$route.params.selesai" @click="checkAll"
                                :checked="pengeluaranDuplicate.find(x => x.id === detailSelected.id) ? pengeluaranDuplicate.find(x => x.id === detailSelected.id)?.noSeriSelected?.length === noseri.length : false">
                        </template>
                        <template #item.no="{ item }">
                            <input type="checkbox" @click="checkedOne(item)"
                                :checked="pengeluaranDuplicate.find(x => x.id === detailSelected.id) ? pengeluaranDuplicate?.find(x => x.id === detailSelected.id)?.noSeriSelected?.find(x => x.id === item.id) : false"
                                v-if="!$route.params.selesai">
                        </template>
                        <template #item.waktu_ambil="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_ambil) }}
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>