<script>
import noseri from './noseri.vue';
export default {
    props: ['detail'],
    components: {
        noseri
    },
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Jumlah No. Seri Dipilih',
                    value: 'noseri'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [
                {
                    no: 1,
                    nama_produk: 'Produk 1',
                    jumlah: 10
                },
                {
                    no: 2,
                    nama_produk: 'Produk 2',
                    jumlah: 20
                },
                {
                    no: 3,
                    nama_produk: 'Produk 3',
                    jumlah: 30
                }
            ],
            search: '',
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailDisiapkan').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        openNoSeri(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show');
            });
        }
    },
}
</script>
<template>
    <div>
        <noseri v-if="showModal" @close="showModal = false" />
        <div class="modal fade modalDetailDisiapkan" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Permintaan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    <div class="col"><label for="">Nomor Permintaan</label>
                                        <div class="card nomor-so">
                                            <div class="card-body"><span id="so">{{ detail.no_permintaan }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Nama Bagian</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body"><span id="akn">{{ detail.nama_bagian }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-cols-3">
                                    <div class="col"><label for="">Tanggal Permintaan</label>
                                        <div class="card nomor-po">
                                            <div class="card-body"><span id="po">{{ detail.tgl_permintaan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Jenis</label>
                                        <div class="card instansi">
                                            <div class="card-body"><span id="instansi" class="text-capitalize">{{
            detail.jenis }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Durasi</label>
                                        <div class="card text-white" style="background-color: rgb(170, 119, 98);">
                                            <div class="card-body"><span id="instansi">3 Hari</span></div>
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
                                <data-table :headers="headers" :items="items" :search="search">
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-qrcode"></i>
                                            Nomor Seri
                                        </button>
                                    </template>
                                    <template #item.noseri="{ item }">
                                        <div>
                                            {{ item.noseri?.length ?? 0 }}
                                        </div>
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