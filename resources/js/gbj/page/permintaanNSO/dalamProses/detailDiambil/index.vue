<script>
import detailnoseri from './detailnoseri.vue';
export default {
    props: ['detail'],
    components: {
        detailnoseri
    },
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
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
            items: [
                {
                    id: 1,
                    nama: 'Produk 1',
                    jumlah: 10
                },
                {
                    id: 2,
                    nama: 'Produk 2',
                    jumlah: 20
                },
                {
                    id: 3,
                    nama: 'Produk 3',
                    jumlah: 30
                }
            ],
            search: '',
            produkSelected: [],
            checkAll: false,
            showModal: false,
            detailSelected: {},
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailDiambil').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            this.produkSelected = this.checkAll ? this.items : [];
        },
        checkedItem(item) {
            if (this.produkSelected.find(produk => produk.id == item.id)) {
                this.produkSelected = this.produkSelected.filter(produk => produk.id != item.id);
            } else {
                this.produkSelected.push(item);
            }
        },
        openDetailNoSeri(item) {
            this.detailSelected = item;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalDetailDiambil').modal('hide');
                $('.modalDetailNoSeri').modal('show');
            });
        },
        closeDetailNoSeri() {
            this.showModal = false;
            this.$nextTick(() => {
                $('.modalDetailDiambil').modal('show');
            });
        }
    },
    watch: {
        produkSelected() {
            if (this.produkSelected.length == this.items.length) {
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
        <detailnoseri v-if="showModal" @close="closeDetailNoSeri" :detail="detailSelected" />
        <div class="modal fade modalDetailDiambil" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Penyerahan Barang</h5>
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
                                <div class="row" :class="detail.jenis == 'peminjaman' ? 'row-cols-3' : 'row-cols-2'">
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
                                    <div class="col" v-if="detail.jenis == 'peminjaman'"><label for="">Durasi</label>
                                        <div class="card text-white" style="background-color: rgb(170, 119, 98);">
                                            <div class="card-body"><span id="instansi">{{ detail.durasi }}</span></div>
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
                                    <template #header.id>
                                        <div>
                                            <input type="checkbox" :checked="checkAll" @click="checkedAll">
                                        </div>
                                    </template>
                                    <template #item.id="{ item }">
                                        <input type="checkbox"
                                            :checked="produkSelected && produkSelected.find(produk => produk.id == item.id)"
                                            @click="checkedItem(item)">
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-sm btn-outline-primary" @click="openDetailNoSeri(item)">
                                            <i class="fas fa-eye"></i>
                                            Detail
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