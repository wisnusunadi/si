<script>
export default {
    props: ["dataTable"],
    data() {
        return {
            headers: [
                { text: "No", value: "no" },
                { text: "Kode Alat", value: "kode" },
                { text: "Nama Alat", value: "nama" },
                { text: "Aksi", value: "aksi", sortable: false },
            ]
        }
    },
    methods: {
        edit(id) {
            this.$emit("edit", id);
        },
        hapus(id) {
            this.$swal({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$emit("hapus", id);
                    this.$swal(
                        "Terhapus!",
                        "Data berhasil dihapus.",
                        "success"
                    );
                }
            });
        },
        detail(id) {
            this.$router.push({ name: "master-alat-detail", params: { id: id } });
        },
        status(status){
            return status ? 'Aktif' : 'Tidak Aktif';
        }
    },
};
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Alat</th>
                    <th>Nama Alat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode }}</td>
                    <td>{{ item.nama }}</td>

                    <td>
                        <!-- <button
                            class="btn btn-outline-info"
                            @click="detail(index)"
                        >
                            <i class="fas fa-eye"></i>
                        </button> -->
                        <button
                            class="btn btn-outline-warning"
                            @click="edit(item.id)"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Data Kosong</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
