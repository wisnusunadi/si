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
    },
}
</script>
<template>
    <div>
        <cetakSJ v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <div class="d-flex flex-row-reverse bd-highlight">
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
    </div>
</template>