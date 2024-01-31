<script>
import Header from '../../components/header.vue';
import cetakseri from './create.vue';
import pilihan from '../perakitanBerlangsung/riwayat/modalPilihan.vue'
import riwayat from './riwayat.vue';
import seriviatext from '../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue';
export default {
    components: {
        Header,
        cetakseri,
        pilihan,
        riwayat,
        seriviatext
    },
    data() {
        return {
            title: 'Cetak No. Seri',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Cetak No. Seri',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                {
                    text: 'Id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                },
                {
                    text: 'Keperluan',
                    value: 'keperluan',
                },
                {
                    text: 'Tanggal Buat',
                    value: 'tanggal_buat',
                },
                {
                    text: 'Operator',
                    value: 'operator',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            items: [
                {
                    id: 1,
                    noseri: 'TD16241D00133',
                    keperluan: 'Cetak Outer Box',
                    tanggal_buat: '23 September 2021',
                    operator: 'Fransisca Angelina Yustin'
                }
            ],
            noSeriSelected: [],
            showModal: false,
            checkAll: false,
            showModalPilihan: false,
            showModalRiwayat: false,
            selectRiwayat: null,
            showModalSeriViaText: false,    
        }
    },
    methods: {
        openModalCreate() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalcetak').modal('show');
            });
        },
        selectNoSeri(id) {
            if (this.noSeriSelected.find((data) => data === id)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data !== id);
            } else {
                this.noSeriSelected.push(id);
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.items.map((data) => data.id);
            } else {
                this.noSeriSelected = [];
            }
        },
        openModalPilihan() {
            this.showModalPilihan = true;
            this.$nextTick(() => {
                $('.modalPilihan').modal('show');
            });
        },
        openRiwayat(item) {
            this.selectRiwayat = item;
            this.showModalRiwayat = true;
            this.$nextTick(() => {
                $('.modalRiwayat').modal('show');
            });
        },
        openModalSeriText() {
            this.showModalSeriViaText = true;
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            });
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/)
            let noseridouble = []
            let noserinotfound = []

            noseriarray = noseriarray.filter((data) => {
                return data !== '' && data !== null
            })

            noseriarray.forEach((item, index) => {
                if(noseriarray.indexOf(item) !== index) {
                    noseridouble.push(item)
                }
            })

            if (noseridouble.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noseridouble.join(', ')} duplikat`, 'warning')
            }

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.items.length; j++) {
                    if (noseriarray[i] === this.items[j].noseri) {
                        found = true
                        this.selectNoSeri(this.items[j].id)
                    }else{
                        found = false
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noserinotfound.join(', ')} tidak ditemukan`, 'warning')
            }

        }
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.items.length) {
                this.checkAll = true;
            } else {
                this.checkAll = false;
            }
        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <cetakseri v-if="showModal" @closeModal="showModal = false" />
        <pilihan v-if="showModalPilihan" @closeModal="showModalPilihan = false" :data="noSeriSelected" />
        <riwayat v-if="showModalRiwayat" :riwayat="selectRiwayat" @closeModal="showModalRiwayat = false" />
        <seriviatext v-if="showModalSeriViaText" @closeModal="showModalSeriViaText = false" @submit="submit" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-outline-primary btn-sm" 
                        @click="openModalPilihan"
                        v-if="noSeriSelected.length > 0">
                            <i class="fas fa-print"></i>
                            Cetak No. Seri
                        </button>
                        <button class="btn btn-outline-info btn-sm" @click="openModalSeriText">Pilih Nomor Seri Via Text</button>
                        <button class="btn btn-primary btn-sm" @click="openModalCreate">
                            <i class="fas fa-plus"></i>
                            Tambah No. Seri
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                    </div>
                </div>
                <data-table :headers="headers" :items="items" :search="search">
                    <template #header.id>
                        <div>
                            <input type="checkbox" :checked="checkAll" @click="checkedAll">
                        </div>
                    </template>
                    <template #item.id="{ item }">
                        <div>
                            <input type="checkbox" 
                            @click="selectNoSeri(item.id)"
                            :checked="noSeriSelected && noSeriSelected.find((noseri) => noseri === item.id)" />
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-print"></i>
                                Cetak No. Seri
                            </button>
                            <button class="btn btn-outline-info btn-sm" @click="openRiwayat(item)">
                                <i class="fas fa-rounded fa-info-circle"></i>
                                Riwayat Cetak No. Seri
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>