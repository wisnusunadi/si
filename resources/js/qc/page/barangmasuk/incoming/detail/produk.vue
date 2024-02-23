<script>
export default {
    data() {
        return {
            headers: [
                {
                    text: 'Tanggal Terima',
                    value: 'tgl_terima'
                },
                {
                    text: 'Waktu Terima',
                    value: 'waktu_terima'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            pengemasan: [
                {
                    id: 1,
                    tgl_terima: '13 Desember 2023',
                    waktu_terima: '12:00',
                    jumlah: 100
                },
                {
                    id: 2,
                    tgl_terima: '14 Desember 2023',
                    waktu_terima: '12:00',
                    jumlah: 100
                },
                {
                    id: 3,
                    tgl_terima: '15 Desember 2023',
                    waktu_terima: '12:00',
                    jumlah: 100
                }
            ],
            searchPengemasan: '',
            searchNoSeri: '',
            headersNoSeri: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
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
            noseri: [],
            filterNoSeri: [],
            noSeriSelected: [],
            checkAll: false,
        }
    },
    methods: {
        detail(item) {
            this.noseri = []
            for (let k = 0; k < 15 + item.id; k++) {
                if (k < 5) {
                    this.noseri.push({
                        id: `${item.id}${k}`,
                        noseri: `TD12${item.id}${k}`,
                        hasil: 'belum_diuji'
                    });
                } else if (k < 10) {
                    this.noseri.push({
                        id: `${item.id}${k}`,
                        noseri: `TD12${item.id}${k}`,
                        hasil: 'ok'
                    });
                } else {
                    this.noseri.push({
                        id: `${item.id}${k}`,
                        noseri: `TD12${item.id}${k}`,
                        hasil: 'not_ok'
                    });
                }
            }
            const index = this.pengemasan.findIndex((i) => i.id === item.id);
            this.pengemasan[index].noseri = this.noseri.length;
        },
        clickFilterHasil(filter) {
            if (this.filterNoSeri.includes(filter)) {
                this.filterNoSeri = this.filterNoSeri.filter((item) => item !== filter);
            } else {
                this.filterNoSeri.push(filter);
            }
        },
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
                        class: 'text-success fa fa-check'
                    }
                case 'not_ok':
                    return {
                        text: 'Tidak Lolos',
                        class: 'text-danger fa fa-times'
                    }
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.noseri;
            } else {
                this.noSeriSelected = [];
            }
        },
        checkedItem(item) {
            if (this.noSeriSelected.includes(item)) {
                this.noSeriSelected = this.noSeriSelected.filter((i) => i.id !== item.id);
            } else {
                this.noSeriSelected.push(item);
            }
        }
    },
    computed: {
        filteredDalamProses() {
            let filtered = this.noseri;
            if (this.filterNoSeri.length > 0) {
                filtered = filtered.filter((item) => this.filterNoSeri.includes(item.hasil));
            }
            return filtered;
        },
        getAllStatusUnique() {
            return [...new Set(this.noseri.map((item) => item.hasil))]
        },
    },
    watch: {
        detailSelected() {
            // cek jika object key noseri sudah ada dan sama dengan filteredDalamProses maka checkedAll = true
            if (this.detailSelected.noseri && this.detailSelected.noseri.length === this.filteredDalamProses.length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    }
}
</script>
<template>
    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <button class="btn btn-info" v-if="noSeriSelected.length > 0">
                                <i class="fa fa-pencil"></i>
                                Uji Unit</button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchPengemasan" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="pengemasan" :search="searchPengemasan">
                        <template #item.aksi="{ item }">
                            <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                                <i class="fa fa-eye"></i>
                                Detail
                            </button>
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
                            <span class="float-left filter"><button data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="btn btn-sm btn-outline-info"><i class="fas fa-filter"></i>
                                    Filter
                                </button>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group"><label for="jenis_penjualan">Keterangan</label></div>
                                            <div class="form-group">
                                                <div class="form-check" v-for="item in getAllStatusUnique" :key="item">
                                                    <input type="checkbox" id="status1"
                                                        :checked="filterNoSeri.includes(item)"
                                                        @click="clickFilterHasil(item)" class="form-check-input"><label
                                                        for="status1" class="form-check-label text-uppercase">
                                                        {{ statusText(item).text }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                            <button class="btn btn-sm btn-primary ml-1">Pilih Nomor Seri Via Text</button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchNoSeri" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headersNoSeri" :items="filteredDalamProses" :search="searchNoSeri">
                        <template #header.id>
                            <input type="checkbox" :checked="checkAll" @click="checkedAll" />
                        </template>
                        <template #item.id="{ item }">

                            <input type="checkbox" :checked="noSeriSelected && noSeriSelected.find((i) => i.id === item.id)"
                                @click="checkedItem(item)" />

                        </template>
                        <template #item.hasil="{ item }">
                            <div>
                                <i :class="statusText(item.hasil).class"></i>
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>