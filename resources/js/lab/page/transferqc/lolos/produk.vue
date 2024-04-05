<script>
import seri from "./seri.vue";
import axios from "axios";
export default {
    components: {
        seri,
    },
    props: {
        headerSO: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            search: "",
            modalSeri: false,
            produkSelected: null,
            produk: [],
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "Produk",
                    value: "nama",
                },
                {
                    text: "Jumlah",
                    value: "jumlah",
                },
                {
                    text: "Jumlah No. Seri Dipilih",
                    value: "noseri",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ]
        };
    },
    methods: {
        closeModal() {
            $(".modalProduk").modal("hide");
            this.$emit("close");
        },
        detailNoSeri(data) {
            $(".modalProduk").modal("hide");
            this.produkSelected = JSON.parse(JSON.stringify(data));
            this.modalSeri = true;
            this.$nextTick(() => {
                $(".modalSeri").modal("show");
            });
        },
        closeModalSeri() {
            $(".modalSeri").modal("hide");
            this.modalSeri = false;
            $(".modalProduk").modal("show");
        },
        simpanSeri(produk) {
            let index = this.produk.findIndex((data) => data.nama === produk.nama);
            this.produk[index] = JSON.parse(JSON.stringify(produk));
            this.closeModalSeri();
            // make spacing on this.search
            this.search = "&nbsp;";
            setTimeout(() => {
                this.search = "";
            }, 1);
        },
        async simpan() {
            // check if produk.jenis === 'produk' must be !data?.noseri || data.noseri.length === 0
            const produk = [];

            this.produk.forEach((data) => {
                if (data?.noseri || data.noseri?.length > 0) {
                    produk.push(data);
                } else if (data.jenis != 'produk') {
                    produk.push(data);
                }
            });

            if (produk.length === 0) {
                this.$swal('Error', 'Silahkan pilih noseri produk', 'error');
                return;
            }

            try {
                const { data } = await axios.post("/api/qc/tf/ok", {
                    header: this.headerSO,
                    produk,
                });
                this.$swal('Berhasil', data.message, 'success');
                this.$emit("refresh");
                this.closeModal();
            } catch (error) {
                this.$swal('Error', error.response.data.message, 'error');
            }
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/qc/tf/data/ok/${this.headerSO.id}`);
                this.produk = data.map((data, index) => {
                    return {
                        no: index + 1,
                        ...data,
                    };
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    created() {
        this.getData();
    },
};
</script>
<template>
    <div v-if="!$store.state.loading">
        <seri v-if="modalSeri" @close="closeModalSeri" :produk="produkSelected" @simpan="simpanSeri" />
        <div class="modal fade modalProduk" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Customer</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{
        headerSO.customer
    }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor SO</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="po">{{
            headerSO.so
        }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{
            headerSO.no_po
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
                                            <input type="text" class="form-control" placeholder="Cari..."
                                                v-model="search" />
                                        </div>
                                    </div>
                                </div>
                                <data-table :headers="headers" :items="produk" :search="search">
                                    <template #item.noseri="{item}">
                                        <div v-if="item.jenis == 'produk'">
                                            {{ item.noseri?.length ?? 0 }}
                                        </div>
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <div>
                                            <button @click="detailNoSeri(item)" class="btn btn-primary btn-sm"
                                                v-if="item.jenis === 'produk'">
                                                <i class="fa fa-qrcode"></i>
                                                Nomor Seri
                                            </button>
                                        </div>
                                    </template>
                                </data-table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Keluar
                        </button>
                        <button type="button" class="btn btn-primary" @click="simpan">
                            Transfer
                        </button>
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
</style>
