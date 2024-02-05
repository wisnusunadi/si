<script>
export default {
    props: ["dataTable"],
    methods: {
        edit(id) {
            this.$emit("edit", id);
        },
        detail(id) {
            this.$router.push({ name: "master-dokumen-detail", params: { id: id } });
        },
    },
};
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Metode Kerja</th>
                    <th>Dokumen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.metode }}</td>
                    <td>{{ data.no_dokumen }}</td>
                    <td>
                        <button
                            class="btn btn-outline-info"
                            @click="detail(data.id)"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning" @click="edit(data.id)">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="2" class="text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
