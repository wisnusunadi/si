<script>
import modalNoSeri from '../detail/noseri.vue'
export default {
    props: ['pengembalian'],
    components: {
        modalNoSeri
    },
    data() {
        return {
            search: '',
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
            detailSelected: {},
            showModal: false
        }
    },
    methods: {
        closeModal() {
            $('.modalPengembalian').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        openNoSeri(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalPengembalian').modal('hide')
                $('.modalNoSeri').modal('show')
            })
        },
        closeModalNoSeri() {
            $('.modalPengembalian').modal('show')
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
        <modalNoSeri v-if="showModal" @close="closeModalNoSeri" :detail="detailSelected" @simpan="simpanNoSeri" />
        <div class="modal fade modalPengembalian" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengembalian Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row"
                                    :class="pengembalian.tujuan == 'Peminjaman' ? 'row-cols-2' : 'row-cols-3'">
                                    <div class="col"><label for="">Nomor Referensi</label>
                                        <div class="card nomor-so">
                                            <div class="card-body"><span id="total_tf">{{ pengembalian.noref }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Tanggal Permintaan</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body"><span id="belum_tf">{{ pengembalian.tanggal }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Tujuan Permintaan</label>
                                        <div class="card nomor-po">
                                            <div class="card-body"><span id="sudah_tf">{{ pengembalian.tujuan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col" v-if="pengembalian.tujuan == 'Peminjaman'"><label for="">Lama
                                            Peminjaman</label>
                                        <div class="card instansi">
                                            <div class="card-body"><span id="instansi">{{ pengembalian?.lama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>