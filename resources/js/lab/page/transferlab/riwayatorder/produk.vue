<script>
import seri from './seri.vue';
export default {
    props: ["headerSO"],
    components: {
        seri,
    },
    data() {
        return {
            search: "",
            modalseri: false,
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
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
            search: "",
            noSeriSelected: null,
        }
    },
    methods: {
        closeModal() {
            $(".modalRiwayatProduk").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        openModalSeri(data) {
            this.noSeriSelected = data;
            this.modalseri = true;
            this.$nextTick(() => {
                $(".modalRiwayatSeri").modal("show");
                $(".modalRiwayatProduk").modal("hide");
            });
        },
        closeModalSeri() {
            this.modalseri = false;
            this.$nextTick(() => {
                $(".modalRiwayatProduk").modal("show");
            });
        }
    },
}
</script>
<template>
    <div>
        <seri v-if="modalseri" @close="closeModalSeri" :noseri="noSeriSelected" />
        <div class="modal fade modalRiwayatProduk" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                                    </div>
                                </div>
                                <data-table :headers="headers" :items="headerSO?.detail" :search="search">
                                    <template #item.aksi="{ item }">
                                        <div>
                                            <button class="btn btn-outline-primary" @click="openModalSeri(item.noseri)">
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
        </div>
    </div>
</template>