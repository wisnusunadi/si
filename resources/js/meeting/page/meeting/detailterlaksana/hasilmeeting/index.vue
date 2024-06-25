<script>
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
            renderPaginate: [],
            showModal: false,
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
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
                    this.$_delete(`/api/hr/meet/hasil/${id}`)
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
    computed: {
        paginateData() {
            return this.meeting.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
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
                    <button
                        class="btn btn-primary"
                        @click="addHasilMeeting"
                        v-if="status == 'menyusun_hasil_meeting'"
                    >
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
                <div class="p-2">
                    <input
                        v-if="status != 'menyusun_hasil_meeting'"
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <table class="table text-center">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th class="text-justify" style="width: 1200px;">Hasil Rapat</th>
                        <th v-if="status == 'menyusun_hasil_meeting'">Aksi</th>
                    </tr>
                </thead>
                <tbody v-if="status == 'menyusun_hasil_meeting'">
                    <tr v-for="(item, idx) in meeting" :key="idx" >
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-justify">{{ item.isi }}</td>
                        <td>
                            <button
                                class="btn btn-outline-warning"
                                @click="editHasilMeeting(item, idx)"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                class="btn btn-outline-danger"
                                @click="deleteHasilMeeting(item.id)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-if="status != 'menyusun_hasil_meeting'">
                    <tr v-for="(item, idx) in renderPaginate" :key="idx">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-justify">{{ item.isi }}</td>
                    </tr>
                </tbody>
            </table>
            <pagination
                :filteredDalamProses="paginateData"
                @updateFilteredDalamProses="updatePage"
                v-if="status != 'menyusun_hasil_meeting'"
            />
        </div>
    </div>
</template>