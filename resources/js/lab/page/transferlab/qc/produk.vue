<script>
import noseri from "./noseri.vue";
export default {
    props: ["headerSO"],
    components: {
        noseri,
    },
    data() {
        return {
            search: "",
            modalseri: false,
            noseriSelected: null,
            headers: [
                {
                    text: 'No',
                    value: 'no'
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
            ]
        };
    },
    methods: {
        closeModal() {
            $(".modalRiwayatProduk").modal("hide");
            this.$emit("close");
        },
        detailSeri(data) {
            $(".modalRiwayatProduk").modal("hide");
            this.noseriSelected = data
            this.modalseri = true;
            this.$nextTick(() => {
                $(".modalRiwayatSeri").modal("show");
            });
        },
        closeModalSeri() {
            this.modalseri = false;
            $(".modalRiwayatProduk").modal("show");
        }
    },
};
</script>
<template>
    <div>
        <noseri v-if="modalseri" @close="closeModalSeri" :noseri="noseriSelected" />
        <div class="modal fade modalRiwayatProduk" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-header">
                            <div class="row row-cols-2">
                                <div class="col">
                                    <label for="">
                                        Nomor SO
                                    </label>
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            <span id="so">{{
                                                headerSO.so
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <label for="">
                                        Nomor PO
                                    </label>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <span id="akn">{{
                                                headerSO.no_po
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <label for="">Tanggal Transfer</label>
                                    <div class="card nomor-po">
                                        <div class="card-body">
                                            <span id="po">{{
                                                formatDate(
                                                    headerSO.tgl_transfer
                                                )
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <label for="">Customer</label>
                                    <div class="card instansi">
                                        <div class="card-body">
                                            <span id="instansi">{{
                                                headerSO.customer
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                                    </div>
                                </div>
                            </div>
                            <data-table :headers="headers" :items="headerSO.detail" :search="search">
                                <template #item.aksi="{ item }">
                                    <div>
                                        <button class="btn btn-primary btn-sm" @click="detailSeri(item.noseri)">
                                            <i class="fa fa-qrcode"></i>
                                            Nomor Seri
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
</template>
<style>
.nomor-so {
    background-color: #717fe1;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-akn {
    background-color: #df7458;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-po {
    background-color: #85d296;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.instansi {
    background-color: #36425e;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>
