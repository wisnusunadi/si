<script>
import moment from 'moment';
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
                    waktu_kembali: '2024-08-26 13:00:00',
                    tgl_close: '2024-08-26 13:00:00',
                },
                {
                    no: 2,
                    noseri: 'NS-2021080002',
                    waktu_kembali: '2024-08-26 13:00:00',
                    tgl_close: '2024-09-03 13:00:00',
                }
            ]
        },
        cekDurasiPengembalian(tgl_close, tgl_kembali) {
            const waktuKembali = moment(tgl_kembali);
            const waktuClose = moment(tgl_close);

            const durasi = waktuKembali.diff(waktuClose, 'days');

            // iterasi dari tgl_close sampai tgl_kembali
            let weekend = 0;
            let currentDate = waktuClose.clone();
            while (currentDate.isBefore(waktuKembali)) {
                if (currentDate.day() === 0 || currentDate.day() === 6) {
                    weekend++;
                }
                currentDate.add(1, 'days');
            }

            const durasiAkhir = durasi - weekend;

            if (durasiAkhir > 0) {
                return {
                    text: `Lebih ${durasiAkhir} Hari`,
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (durasiAkhir < 0) {
                return {
                    text: `Kurang ${Math.abs(durasiAkhir)} Hari`,
                    color: 'black--text',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Tepat Waktu',
                    color: 'text-primary',
                    icon: 'fas fa-check-circle'
                }
            }
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
                            <div :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).color">
                                {{ dateFormat(item.waktu_kembali) }}
                            </div>
                            <small :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).color">
                                <i :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).icon"></i>
                                {{ cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).text }}
                            </small>
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
                        <template #item.waktu_kembali="{ item }">
                            <div v-if="item.waktu_kembali">
                                <div :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).color">
                                    {{ dateFormat(item.waktu_kembali) }}
                                </div>
                                <small :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).color">
                                    <i :class="cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).icon"></i>
                                    {{ cekDurasiPengembalian(item.tgl_close, item.waktu_kembali).text }}
                                </small>
                            </div>
                            <div v-else></div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>