<script>
import modalChecked from '../../../../../lab/page/kalibrasi/kalibrasi_internal/detail/modalchecked.vue'
import modalSeri from './modal.vue'
export default {
    props: ['detailBarangMasuk'],
    components: {
        modalSeri,
        modalChecked
    },
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Tanggal Terima',
                    value: 'tgl_terima',
                    sortable: false
                },
                {
                    text: 'Waktu Terima',
                    value: 'waktu_terima',
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                },
                {
                    text: 'Tanggal Uji',
                    value: 'tgl_uji',
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
                    value: 'penguji'
                }
            ],
            pengemasan: JSON.parse(JSON.stringify(this.detailBarangMasuk.pengemasan)),
            search: '',
            filterNoSeri: [],
            noSeriSelected: [],
            checkAll: false,
            showModal: false,
            tanggalAwalTerima: '',
            tanggalAkhirTerima: '',
            tanggalAwalUji: '',
            tanggalAkhirUji: '',
            showModalSeri: false
        }
    },
    methods: {
        clickFilterHasil(filter) {
            if (this.filterNoSeri.includes(filter)) {
                this.filterNoSeri = this.filterNoSeri.filter((item) => item !== filter);
            } else {
                this.filterNoSeri.push(filter);
            }
        },
        statusText(status) {
            switch (status) {
                case 'belum':
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
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.pengemasan.filter((item) => item.hasil === 'belum');
            } else {
                this.noSeriSelected = [];
            }
        },
        checkedItem(item) {
            if (this.noSeriSelected.find((x) => x.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((x) => x.id !== item.id);
            } else {
                this.noSeriSelected.push(item);
            }
        },
        openUjiUnit() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalIncoming').modal('show');
            });
        },
        openModalSeri() {
            this.showModalSeri = true;
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            });
        },
        submit(noseri) {
            let noserinotfound = []
            let noseriarray = noseri.split(/[\n, \t]/);
            noseriarray = noseriarray.filter((item) => item != "");
            noseriarray = [...new Set(noseriarray)];
            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.pengemasan.filter((item) => item.hasil == 'belum').length; j++) {
                    if (noseriarray[i] == this.pengemasan.filter((item) => item.hasil == 'belum')[j].noseri) {
                        this.checkedItem(this.pengemasan.filter((item) => item.hasil == 'belum')[j])
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }
            if (noserinotfound.length > 0) {
                swal.fire('Peringatan', 'No Seri ' + noserinotfound.join(', ') + ' tidak ditemukan atau sudah diuji', 'warning')
            }
        }
    },
    computed: {
        filteredDalamProses() {
            let filtered = this.pengemasan;
            if (this.filterNoSeri.length > 0) {
                filtered = filtered.filter((item) => this.filterNoSeri.includes(item.hasil));
            }

            if (this.tanggalAwalTerima && this.tanggalAkhirTerima) {
                const startDate = new Date(this.tanggalAwalTerima);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhirTerima);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_terima);
                    return date >= startDate && date <= endDate;
                });
            } else if (this.tanggalAwalTerima) {
                const startDate = new Date(this.tanggalAwalTerima);
                startDate.setHours(0, 0, 0, 0);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_terima);
                    return date >= startDate;
                });
            } else if (this.tanggalAkhirTerima) {
                const endDate = new Date(this.tanggalAkhirTerima);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_terima);
                    return date <= endDate;
                });
            }

            if (this.tanggalAwalUji && this.tanggalAkhirUji) {
                const startDate = new Date(this.tanggalAwalUji);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhirUji);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_uji);
                    return date >= startDate && date <= endDate;
                });
            } else if (this.tanggalAwalUji) {
                const startDate = new Date(this.tanggalAwalUji);
                startDate.setHours(0, 0, 0, 0);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_uji);
                    return date >= startDate;
                });
            } else if (this.tanggalAkhirUji) {
                const endDate = new Date(this.tanggalAkhirUji);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_uji);
                    return date <= endDate;
                });
            }


            return filtered;
        },
        getAllStatusUnique() {
            return [...new Set(this.pengemasan.map((item) => item.hasil))]
        },

    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length == this.pengemasan.filter((item) => item.hasil === 'belum').length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    }
}
</script>
<template>
    <div class="card">
        <modalSeri v-if="showModal" @closeModal="showModal = false" :header="detailBarangMasuk.header"
            :noseri="noSeriSelected" />
        <modalChecked v-if="showModalSeri" @close="showModalSeri = false" @submit="submit" />
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <button class="btn btn-primary" @click="openModalSeri" v-if="!$route.params.isRiwayat">Pilih No Seri Via Text</button>
                    <button class="btn btn-info" v-if="noSeriSelected.length > 0" @click="openUjiUnit">
                        <i class="fa fa-pencil"></i>
                        Uji Unit</button>
                </div>
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <data-table :headers="headers" :items="filteredDalamProses" :search="search">
                <template #header.id>
                    <input type="checkbox" :checked="checkAll" @click="checkedAll"
                        v-if="pengemasan.filter((item) => item.hasil === 'belum').length > 0 && !$route.params.isRiwayat" />
                    <span v-else></span>
                </template>
                <template #header.tgl_terima>
                    <span class="text-bold pr-2">Tanggal Terima</span>
                    <span class="filter">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>
                        </a>
                        <form id="filter_ekat">
                            <div class="dropdown-menu">
                                <div class="px-3 py-3 font-weight-normal">
                                    <div class="form-group">
                                        <label for="">Tanggal Awal</label>
                                        <input type="date" class="form-control" v-model="tanggalAwalTerima">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Akhir</label>
                                        <input type="date" class="form-control" v-model="tanggalAkhirTerima">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </span>
                </template>
                <template #header.tgl_uji>
                    <span class="text-bold pr-2">Tanggal Uji</span>
                    <span class="filter">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>
                        </a>
                        <form id="filter_ekat">
                            <div class="dropdown-menu">
                                <div class="px-3 py-3 font-weight-normal">
                                    <div class="form-group">
                                        <label for="">Tanggal Awal</label>
                                        <input type="date" class="form-control" v-model="tanggalAwalUji">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Akhir</label>
                                        <input type="date" class="form-control" v-model="tanggalAkhirUji">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </span>
                </template>
                <template #header.hasil>
                    <span class="text-bold pr-2">Hasil</span>
                    <span class="filter">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter"></i>
                        </a>
                        <form id="filter_ekat">
                            <div class="dropdown-menu">
                                <div class="px-3 py-3 font-weight-normal">
                                    <div class="form-check" v-for="status in getAllStatusUnique" :key="status">
                                        <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                            @click="clickFilterHasil(status)" :checked="filterNoSeri.includes(status)" />
                                        <label class="form-check-label" for="defaultCheck1">
                                            {{ statusText(status).text }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </span>
                </template>
                <template #item.id="{ item }">
                    <input type="checkbox" :checked="noSeriSelected && noSeriSelected.find((x) => x.id === item.id)"
                        v-if="item.hasil === 'belum' && !$route.params.isRiwayat" @click="checkedItem(item)" />
                    <span v-else></span>
                </template>
                <template #item.tgl_terima="{ item }">
                    {{ dateFormat(item.tgl_terima) }}
                </template>
                <template #item.tgl_uji="{ item }">
                    {{ dateFormat(item.tgl_uji) }}
                </template>
                <template #item.hasil="{ item }">
                    <i :class="statusText(item.hasil).class"></i>
                </template>
                <template #item.waktu_uji="{ item }">
                    {{ item.waktu_uji ?? '-' }}
                </template>
                <template #item.aksi="{ item }">
                    <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </template>
            </data-table>
        </div>
    </div>
</template>