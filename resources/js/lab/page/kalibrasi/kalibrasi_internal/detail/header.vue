<script>
import Loading from "../../../../components/loading.vue";
import modalEditPemilik from "./modalEditPemilik.vue";
import modalEditAlamat from "./modalEditAlamat.vue";
import Modal from '../../../master/alat/modal.vue';
export default {
    components: {
        Loading,
        modalEditPemilik,
        modalEditAlamat,
        Modal,
    },
    props: ["header"],
    data() {
        return {
            showEditPemilik: false,
            showEditAlamat: false,
        }
    },
    methods: {
        editPemilik() {
            this.showEditPemilik = true;
            this.$nextTick(() => {
                $(".modalEditPemilik").modal("show");
            });
        },
        editAlamat() {
            this.showEditAlamat = true;
            this.$nextTick(() => {
                $(".modalAlamat").modal("show");
            });
        },
        refresh() {
            this.$emit("refresh");
        },
    },
};
</script>
<template>
    <div class="card">
        <modalEditPemilik :header="header" v-if="showEditPemilik" @close="showEditPemilik = false" @refresh="refresh" />
        <modalEditAlamat :header="header" v-if="showEditAlamat" @close="showEditAlamat = false" />
        <div class="card-body">
            <h2>Info Penjualan</h2>
            <div class="card-text">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight">
                        <p class="text-muted">Customer</p>
                        <b>{{ header.customer }}</b> <br />
                        <span v-if="header.jenis_pemilik.label">{{ JSON.parse(JSON.stringify(header.jenis_pemilik?.label))
                        }}</span>
                        <button class="btn btn-outline-warning btn-sm" @click="editPemilik"
                            v-if="header.jenis_pemilik.label">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <br v-if="header.jenis_pemilik.label">
                        <span>{{ header.nama }}</span> <br />
                        <span class="d-inline-block text-truncate" style="max-width: 150px;">{{ header.alamat }}</span>
                        <button class="btn btn-outline-warning btn-sm" @click="editAlamat" v-if="!header.edit_alamat">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <br />
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No Order</span>
                            <br />
                            <b>{{ header.no_order }}</b>
                        </p>

                        <p>
                            <span class="text-muted">Status</span>
                            <br />
                            <Loading :persentase="header.status" />
                        </p>
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No SO</span>
                            <br />
                            <b>{{ header.so }}</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.progress {
    width: 50%;
}
</style>
