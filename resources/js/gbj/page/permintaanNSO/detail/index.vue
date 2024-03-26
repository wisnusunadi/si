<script>
import modalTolak from './modalTolak.vue'
import noseri from './noseri.vue'
export default {
    components: {
        modalTolak,
        noseri
    },
    data() {
        return {
            showModal: false,
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Jumlah No. Seri Dipilih',
                    value: 'noseri',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            detailProduk: [
                {
                    no: 1,
                    gbj_id: 7,
                    nama: 'Produk 1',
                    jumlah: 10,
                },
                {
                    no: 2,
                    gbj_id: 9,
                    nama: 'Produk 2',
                    jumlah: 20,
                }
            ],
            search: '',
            detailSelected: {},
        }
    },
    props: ['permintaan'],
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        closeModalTolak() {
            $('.modalDetail').modal('show')
            this.showModal = false
        },
        openModalTolak() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalTolak').modal('show')
            })
        },
        closeAllModal() {
            $('.modalTolak').modal('hide')
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
                this.$emit('refresh')
            })
        },
        openNoSeri(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('hide')
                $('.modalNoSeri').modal('show')
            })
        },
        closeModalNoSeri() {
            $('.modalDetail').modal('show')
            this.showModal = false
        },
        simpanNoSeri(produk) {
            this.detailProduk = this.detailProduk.map(item => {
                if (item.gbj_id === produk.gbj_id) {
                    item.noseri = produk.noseri
                }
                return item
            })
        }
    },
}
</script>
<template>
    <div>
        <modalTolak v-if="showModal" @close="closeModalTolak" @closeAllModal="closeAllModal" :permintaan="permintaan" />
        <noseri v-if="showModal" @close="closeModalNoSeri" :detail="detailSelected" @simpan="simpanNoSeri" />
        <div class="modal fade modalDetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Permintaan Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row"
                                    :class="permintaan.tujuan == 'Peminjaman' ? 'row-cols-2' : 'row-cols-3'">
                                    <div class="col"><label for="">Nomor Referensi</label>
                                        <div class="card nomor-so">
                                            <div class="card-body"><span id="total_tf">{{ permintaan.noref }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Tanggal Permintaan</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body"><span id="belum_tf">{{ permintaan.tanggal }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Tujuan Permintaan</label>
                                        <div class="card nomor-po">
                                            <div class="card-body"><span id="sudah_tf">{{ permintaan.tujuan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col" v-if="permintaan.tujuan == 'Peminjaman'"><label for="">Lama
                                            Peminjaman</label>
                                        <div class="card instansi">
                                            <div class="card-body"><span id="instansi">{{ permintaan?.lama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                                    </div>
                                </div>
                                <data-table :headers="headers" :items="detailProduk" :search="search">
                                    <template #item.noseri="{ item }">
                                        {{ item.noseri?.length ?? 0 }}
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-primary" @click="openNoSeri(item)">
                                            <i class="fas fa-qrcode"></i>
                                            Nomor Seri
                                        </button>
                                    </template>
                                </data-table>
                                <div class="d-flex bd-highlight"
                                    v-if="permintaan.status == 'new' || permintaan.status == 'barangdisiapkan' || permintaan.status == 'update'">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <button class="btn btn-danger" @click="openModalTolak">Tolak</button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-primary">Setujui</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>