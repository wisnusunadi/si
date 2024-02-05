<script>
import modal from './modal.vue';
export default {
    props: ["dataTable"],
    data() {
        return {
            modal: false,
            produkSelected: null,
        }
    },
    components: { modal },
    methods: {
        async showModal(produk) {
            this.produkSelected = JSON.parse(JSON.stringify(produk));
            this.modal = true;
            this.$nextTick(() => {
                $(".modalProduk").modal("show");
            });
        },
        refresh() {
            this.$emit("refresh");
        }
    },
};
</script>
<template>
    <div>
        <modal :produk="produkSelected" v-if="modal" @closeModal="modal = false" @refresh="refresh"/>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Alat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.kode_lab?.label }}</td>
                    <td>
                        <!-- edit -->
                        <button class="btn btn-sm btn-outline-warning" @click="showModal(data)">
                            <i class="fa fa-pencil-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
