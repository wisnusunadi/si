<script>
import status from "../../components/status.vue";
import axios from "axios";
export default {
    components: {
        status,
    },
    props: ["approvalData"],
    data() {
        return {
            search: "",
            headers: [
                { text: "No", value: "no", sortable: false },
                { text: "Nomor Meeting", value: "urutan" },
                { text: "Judul Meeting", value: "judul" },
                { text: "Tanggal", value: "tanggal_meet" },
                {
                    text: "Waktu",
                    value: "waktu",
                    children: [
                        { text: "Mulai", value: "mulai" },
                        { text: "Selesai", value: "selesai" },
                    ],
                },
                { text: "Lokasi", value: "lokasi_nama" },
                { text: "Status", value: "status" },
                { text: "Aksi", value: "aksi", sortable: false },
            ],
        };
    },
    methods: {
        approve(item) {
            swal.fire({
                title: "Setujui?",
                text: "Apakah anda yakin ingin menyetujui data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$_post(
                            "/api/hr/meet/jadwal/update/approve_setuju",
                            {
                                id: item.id,
                                notulensi: item.hasil_notulen,
                            },
                        )
                        .then((res) => {
                            swal.fire(
                                "Berhasil!",
                                "Data telah disetujui.",
                                "success"
                            );
                            this.$emit("refresh");
                        })
                        .catch((err) => {
                            swal.fire(
                                "Gagal!",
                                "Data gagal disetujui.",
                                "error"
                            );
                        });
                }
            });
        },
        reject(id) {
            swal.fire({
                title: "Tolak?",
                text: "Apakah anda yakin ingin menolak data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(
                            "/api/hr/meet/jadwal/update/approve_batal",
                            {
                                id,
                            },
                            {
                                headers: {
                                    Authorization: `Bearer ${localStorage.getItem(
                                        "lokal_token"
                                    )}`,
                                },
                            }
                        )
                        .then((res) => {
                            swal.fire(
                                "Berhasil!",
                                "Data telah ditolak.",
                                "success"
                            );
                            this.$emit("refresh");
                        })
                        .catch((err) => {
                            swal.fire("Gagal!", "Data gagal ditolak.", "error");
                        });
                }
            });
        },
    },
};
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input
                    type="text"
                    class="form-control"
                    v-model="search"
                    placeholder="Cari..."
                />
            </div>
        </div>
        <data-table :headers="headers" :items="approvalData" :search="search">
            <template #item.status="{ item }">
                <status :status="item.status" />
            </template>
            <template #item.aksi="{ item }">
                <div>
                    <div
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        id="dropdownMenuButton"
                        aria-haspopup="true"
                        aria-expanded="true"
                    >
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                    >
                        <button
                            class="dropdown-item"
                            type="button"
                            @click="approve(item)"
                        >
                            <i class="fas fa-check"></i>
                            Setujui
                        </button>
                        <button
                            class="dropdown-item"
                            type="button"
                            @click="reject(item.id)"
                        >
                            <i class="fas fa-times"></i>
                            Tolak
                        </button>
                        <button
                            class="dropdown-item"
                            type="button"
                            @click="$emit('detail', item.id, item.status)"
                        >
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </div>
                </div>
            </template>
        </data-table>
    </div>
</template>