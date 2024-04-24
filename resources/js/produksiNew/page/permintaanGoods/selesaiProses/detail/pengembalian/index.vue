<script>
export default {
    data() {
        return {
            search: '',
            headers: [
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
            items: [
                {
                    no: 1,
                    nama: 'Produk 1',
                    jumlah: 10,
                    waktu_kembali: '2024-08-26 13:00:00',
                    tgl_close: '2024-09-03 13:00:00',
                },
                {
                    no: 2,
                    nama: 'Produk 2',
                    jumlah: 20,
                    waktu_kembali: '2024-08-26 13:00:00',
                    tgl_close: '2024-08-26 13:00:00',
                },
                {
                    no: 3,
                    nama: 'Produk 3',
                    jumlah: 5,
                    waktu_kembali: null,
                    tgl_close: null,
                }
            ],
            noseri: [],
            searchNoSeri: '',
            headersNoSeri: [
                {
                    text: 'No.',
                    value: 'id',
                    sortable: false,
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
            detailProdukSelected: {},
            checkedAll: false,
            noSeriSelected: [],
            loading: false,
        }
    },
    methods: {
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
        detailProduk(item) {
            this.detailProdukSelected = item;
            if (item.waktu_kembali) {
                this.noseri = [
                    {
                        no: 1,
                        id: 1,
                        noseri: 'NS-2021080001',
                        waktu_kembali: '2024-08-26 13:00:00',
                        tgl_close: '2024-08-26 13:00:00',
                    },
                    {
                        no: 2,
                        id: 2,
                        noseri: 'NS-2021080002',
                        waktu_kembali: '2024-08-26 13:00:00',
                        tgl_close: '2024-09-03 13:00:00',
                    }
                ]
            } else {
                this.noseri = [
                    {
                        no: 1,
                        id: 1,
                        noseri: 'NS-2021080001',
                        waktu_kembali: null,
                        tgl_close: '2024-08-26 13:00:00',
                    },
                    {
                        no: 2,
                        id: 2,
                        noseri: 'NS-2021080002',
                        waktu_kembali: null,
                        status: 'Menunggu Approval GBJ',
                        tgl_close: '2024-09-03 13:00:00',
                    }
                ]
            }
        },
        checkAll() {
            this.checkedAll = !this.checkedAll;
            if (this.checkedAll) {
                this.noSeriSelected = this.noseri.filter(item => !item.waktu_kembali);
            } else {
                this.noSeriSelected = [];
            }
        },
        checkOne(item) {
            if (item.waktu_kembali) {
                return;
            }

            const index = this.noSeriSelected.findIndex(i => i.id === item.id);
            if (index === -1) {
                this.noSeriSelected.push(item);
            } else {
                this.noSeriSelected.splice(index, 1);
            }
        },
        kembali() {
            swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data yang sudah dikembalikan tidak dapat dikembalikan lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loading = true;
                    swal.fire({
                        title: 'Berhasil',
                        text: 'Data berhasil dikembalikan',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                    });
                    this.noSeriSelected = this.noSeriSelected.map(item => {
                        return {
                            ...item,
                            status: 'Menunggu Approval GBJ'
                        }
                    });
                    this.loading = false;
                }
            });
        },
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.noseri.filter(item => !item.waktu_kembali).length) {
                this.checkedAll = true;
            } else {
                this.checkedAll = false;
            }

            // add to parent
            // const index = this.items.findIndex(i => i.no === this.detailProdukSelected.no);
            // this.$set(this.items, index, {
            //     ...this.detailProdukSelected,
            //     noseri: this.noSeriSelected,
            // });

            console.log('noSeriSelected', this.noSeriSelected);
            console.log('noseri', this.noseri.filter(item => !item.waktu_kembali).length);
        },
        detailProdukSelected() {
            this.noSeriSelected = this.detailProdukSelected?.noseri || [];
        }
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
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="items" :search="search">
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
                        </template>
                        <template #item.aksi="{ item }">
                            <div>
                                <button class="btn btn-sm btn-outline-primary" @click="detailProduk(item)">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
        <div class="col" v-if="noseri.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <button class="btn btn-primary" @click="kembali" v-if="noSeriSelected.length > 0">
                                <i class="fas fa-check"></i>
                                Kembalikan
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchNoSeri" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headersNoSeri" :items="noseri" :search="searchNoSeri" v-if="!loading">
                        <template #header.id>
                            <input type="checkbox" :checked="checkedAll" @click="checkAll"
                                v-if="!detailProdukSelected?.waktu_kembali">
                        </template>
                        <template #item.id="{ item }">
                            <input type="checkbox" :checked="noSeriSelected.find(i => i.id === item.id)"
                                @click="checkOne(item)" v-if="!item.waktu_kembali && !item?.status">
                            <div v-if="detailProdukSelected?.waktu_kembali">{{ item.no }}</div>
                        </template>
                        <template #item.noseri="{item}">
                            <div>
                                {{ item.noseri }} <br>
                                <small class="text-danger" v-if="item.status">
                                    {{ item.status }}
                                </small>
                            </div>
                        </template>
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