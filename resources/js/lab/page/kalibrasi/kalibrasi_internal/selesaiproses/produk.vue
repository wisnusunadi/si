<script>
import noseri from './noseri.vue';
export default {
    components: {
        noseri,
    },
    props: ['produk'],
    data() {
        return {
            headers: [
                {
                    text: 'Nama Barang',
                    value: 'nama'
                },
                {
                    text: 'Tipe',
                    value: 'tipe'
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
            search: '',
            showModal: false,
            detailSelected: null
        }
    },
    methods: {
        closeModal() {
            $('.modalProduk').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            })
        },
        detailNoSeri(data) {
            this.showModal = true;
            this.detailSelected = data;
            this.$nextTick(() => {
                $('.modalProduk').modal('hide');
                $('.modalSeri').modal('show');
            })
        },
        closeModalSeri() {
            this.showModal = false;
            this.$nextTick(() => {
                $('.modalProduk').modal('show');
            })
        }
    },
}
</script>
<template>
    <div>
        <noseri v-if="showModal" @closeModal="closeModalSeri" :noseri="detailSelected"></noseri>
        <div class="modal fade modalProduk" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                            </div>
                        </div>
                        <data-table :headers="headers" :items="produk.produk" :search="search">
                            <template #item.aksi="{ item }">
                                <div>
                                    <button class="btn btn-outline-primary" @click="detailNoSeri(item.noseri)">
                                        <i class="fa fa-eye"></i>
                                        Detail
                                    </button>
                                </div>
                            </template>
                        </data-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>