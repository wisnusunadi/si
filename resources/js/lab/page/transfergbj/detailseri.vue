<script>
import axios from 'axios';
import pagination from "../../components/pagination.vue";
export default {
    components: {
        pagination,
    },
    props: ["produk"],
    data() {
        return {
            search: "",
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                },
                {
                    text: 'Gagal Uji',
                    value: 'state'
                }
            ]
        };
    },
    methods: {
        closeModal() {
            $(".modalDetailSeri").modal("hide");
            this.$emit("close");
        },
    },
};
</script>
<template>
    <div class="modal fade modalDetailSeri" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri Gagal Uji</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                            </div>
                        </div>
                    </div>
                    <data-table :headers="headers" :items="produk.seri_ganti" :search="search">
                        <template #item.state="{ item }">
                            <div>
                                <span>{{ item.state == 'qc' ? 'Gagal Pengujian QC' : 'Gagal Kalibrasi' }}</span>
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>
