<script>
import Modal from "./modalCreateEdit.vue";
import axios from "axios";
export default {
    components: { Modal },
    props: ["product"],
    data() {
        return {
            search: "",
            header: [
                { text: "id", value: "id", sortable: false },
                { text: "Kode Produk", value: "kode" },
                { text: "Nama", value: "nama" },
                { text: "Kategori", value: "kategori" },
                { text: "Status", value: "status" },
                { text: "Generate", value: "generate_seri" },
            ],
            selectAll: false,
            selectProduct: [],
            showDialog: false,
        };
    },
    methods: {
        getProduct() {
            this.$emit("getProduct");
        },
        checkAll() {
            if (this.selectAll) {
                this.selectProduct = [...this.product];
            } else {
                this.selectProduct = [];
            }
        },
        async deleteProduk() {
            this.$swal({
                text: `Yakin ingin menghapus ${this.selectProduct.length} produk?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const { data } = await axios.delete("/api/produk", {
                            data: this.selectProduct,
                        });
                        this.$swal(
                            "Berhasil",
                            "Produk berhasil dihapus",
                            "success"
                        );
                        this.getProduct();
                    } catch (error) {
                        console.log(error);
                        this.$swal("Gagal", "Produk gagal dihapus", "error");
                    }
                }
            });
        },
        async updateStatus(item) {
            try {
                const { data } = await axios.post("/api/changeStatusProduk", {
                    id: item.id,
                    status: item.status,
                });

                this.$swal("Berhasil", "Status berhasil diubah", "success");
            } catch (error) {
                this.$swal("Gagal", "Status gagal diubah", "error");
            }
        },

        async updateGenerate(item) {
            try {
                const { data } = await axios.post("/api/changeGenerateProduk", {
                    id: item.id,
                    generate_seri: item.generate_seri,
                });

                this.$swal(
                    "Berhasil",
                    "Generate No Seri Produk berhasil diubah",
                    "success"
                );
            } catch (error) {
                this.$swal(
                    "Gagal",
                    "Generate No Seri Produk gagal diubah",
                    "error"
                );
            }
        },
    },
};
</script>
<template>
    <div>
        <Modal
            @closeDialog="showDialog = false"
            @getProduct="getProduct"
            v-if="showDialog"
            :selectProduct="selectProduct"
            :dialogCreate="showDialog"
            :product="product"
        ></Modal>
        <div class="d-flex">
            <v-card flat class="ml-5 mr-auto">
                <v-text-field
                    v-model="search"
                    placeholder="Cari Produk"
                ></v-text-field>
            </v-card>
            <v-card flat>
                <v-btn color="primary" @click="showDialog = true">
                    Tambah atau Edit Produk
                </v-btn>
                <v-btn
                    color="error"
                    v-if="selectProduct.length"
                    @click="deleteProduk"
                >
                    Hapus Produk
                </v-btn>
            </v-card>
        </div>
        <v-data-table
            :headers="header"
            :items="product"
            :search="search"
            :group-by="['kategori']"
        >
            <!-- No Data -->
            <template #no-data>
                <div class="d-flex justify-center">
                    <v-btn color="primary" @click="getProduct">Refresh</v-btn>
                </div>
            </template>

            <template #header.id>
                <th class="text-left">
                    <v-checkbox
                        :indeterminate="
                            selectProduct.length > 0 &&
                            selectProduct.length < product.length
                        "
                        @click.native="checkAll"
                        v-model="selectAll"
                    ></v-checkbox>
                </th>
            </template>

            <template #item.id="{ item }">
                <v-checkbox v-model="selectProduct" :value="item"></v-checkbox>
            </template>

            <template #item.status="{ item }">
                <v-switch
                    @click="updateStatus(item)"
                    v-model="item.status"
                    :label="item.status ? 'Aktif' : 'Tidak Aktif'"
                ></v-switch>
            </template>

            <template #item.generate_seri="{ item }">
                <v-switch
                    @click="updateGenerate(item)"
                    v-model="item.generate_seri"
                    :label="item.generate_seri ? 'Aktif' : 'Tidak Aktif'"
                ></v-switch>
            </template>
        </v-data-table>
    </div>
</template>
