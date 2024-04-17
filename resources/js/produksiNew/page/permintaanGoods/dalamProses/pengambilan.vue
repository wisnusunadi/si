<script>
import status from '../../../components/status.vue';
export default {
    components: {
        status
    },
    props: ['detailSelected'],
    data() {
        return {
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'No. Seri',
                    value: 'no_seri'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Variasi',
                    value: 'variasi'
                }
            ],
            items: [
                {
                    id: 1,
                    no_seri: 'NSO-2021080001',
                    nama_produk: 'Produk 1',
                    variasi: 'Variasi 1'
                },
                {
                    id: 2,
                    no_seri: 'NSO-2021080002',
                    nama_produk: 'Produk 2',
                    variasi: 'Variasi 2'
                },
                {
                    id: 3,
                    no_seri: 'NSO-2021080003',
                    nama_produk: 'Produk 3',
                    variasi: 'Variasi 3'
                }
            ],
            search: '',
            checkAll: false,
            noSeriSelected: []
        }
    },
    methods: {
        closeModal() {
            $('.modalPengambilan').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        checkedAll() {
            this.checkAll = !this.checkAll;
            this.noSeriSelected = this.checkAll ? this.items : [];
        },
        checkOne(item) {
            if (this.noSeriSelected.find((ns) => ns.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((ns) => ns.id !== item.id);
            } else {
                this.noSeriSelected.push(item);
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalPengambilan" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pengambilan Barang</h5>
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
                                        <div class="card-body"><span id="so">SO/EKAT/III/2022/28</span></div>
                                    </div>
                                </div>
                                <div class="col"><label for="">Nomor Referensi</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body"><span id="akn">-</span></div>
                                    </div>
                                </div>
                                <div class="col"><label for="">Tanggal Permintaan</label>
                                    <div class="card nomor-po">
                                        <div class="card-body"><span id="po">STOK CAHAYA MURNI PO001112</span></div>
                                    </div>
                                </div>
                                <div class="col"><label for="">Jenis</label>
                                    <div class="card instansi">
                                        <div class="card-body"><span id="instansi">PT. Cahaya Murni Cemerlang</span>
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
                            <data-table :headers="headers" :items="items" :search="search">
                                <template #header.id>
                                    <div>
                                        <input type="checkbox" :checked="checkAll" @click="checkedAll">
                                    </div>
                                </template>
                                <template #item.id="{ item }">
                                    <input type="checkbox" :checked="noSeriSelected.find((ns) => ns.id === item.id)"
                                        @click="checkOne(item)" />
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
</template>