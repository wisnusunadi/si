<script>
import axios from 'axios';
import pagination from "../../../../components/pagination.vue";
import modal from "./modal.vue";
export default {
    props: ["meeting", "status"],
    components: {
        pagination,
        modal,
    },
    data() {
        return {
            search: "",
            showModal: false,
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "Hasil Rapat",
                    value: "isi",
                    align: "text-center",
                },
            ]
        };
    },
    methods: {
        addHasilMeeting() {
            this.showModal = true;
            // reset formhasilmeeting
            this.formhasilmeeting = {
                isi: "",
            };
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("show");
            });
        },
        close() {
            this.showModal = false;
        },
        editHasilMeeting(data, idx) {
            this.showModal = true;
            this.formhasilmeeting = JSON.parse(JSON.stringify(data));
            this.formhasilmeeting.idx = idx + 1;
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("show");
            });
        },
        deleteHasilMeeting(id) {
            swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/hr/meet/hasil/${id}`)
                        .then((res) => {
                            this.$emit("refresh");
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
    },
};
</script>
<template>
    <div class="card">
        <modal
            :formhasilmeeting="formhasilmeeting"
            @closeModal="showModal = false"
            v-if="showModal"
            @refresh="$emit('refresh')"
        />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                </div>
                <div class="p-2">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <data-table :headers="headers" :items="meeting" :search="search">
                <template #item.no="{item, index}">
                    <div>
                        {{ index + 1 }}
                    </div>
                </template>
            </data-table>
        </div>
    </div>
</template>