<script>
export default {
    props: ['pengembalian'],
    data() {
        return {
            searchProduk: '',
            headersProduk: [
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
                    text: 'Waktu Closing',
                    value: 'waktu_kembali'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],

            searchNoSeri: '',
            headersNoSeri: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                },
                {
                    text: 'Waktu Kembali',
                    value: 'waktu_kembali',
                }
            ],
            noseri: [],
        }
    },
    methods: {
        detailProduk(item) {
            this.noseri = [
                {
                    no: 1,
                    noseri: 'NS-2021080001',
                    waktu_kembali: '2024-08-24 13:00:00',
                },
                {
                    no: 2,
                    noseri: 'NS-2021080002',
                    waktu_kembali: '2024-08-24 13:00:00',
                }
            ]
        },
    },
}
</script>
<template>
    <div class="row">
        <div :class="noseri.length > 0 ? 'col-8' : 'col-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="pengembalian" :search="searchProduk">
                        <template #item.waktu_kembali="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_kembali) }}
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
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>