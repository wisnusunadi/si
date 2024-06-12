<script>
import kehadiran from "../../../../components/kehadiran.vue";
import pagination from "../../../../components/pagination.vue";
import modal from "./modal.vue";
export default {
    props: ["meeting", "status"],
    components: {
        kehadiran,
        modal,
        pagination,
    },
    data() {
        return {
            search: "",
            showModal: false,
            renderPaginate: [],
            formnotulen: null,
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
        close() {
            this.showModal = false;
        },
        kesesuaian(data) {
            this.formnotulen = JSON.parse(JSON.stringify(data));
            this.formnotulen.kesesuaian = "belum_dicek";
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        changeUnderscoreToSpace(value) {
            return value.replace(/_/g, " ");
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
            v-if="showModal"
            :meeting="formnotulen"
            @closeModal="showModal = false"
            @refresh="$emit('refresh')"
        />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2"></div>
                <div class="p-2">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Penanggung Jawab</th>
                        <th rowspan="2" style="width: 600px">Uraian</th>
                        <th colspan="2">Kesesuian</th>
                        <th rowspan="2" style="width: 500px">Catatan</th>
                        <th
                            rowspan="2"
                            v-if="status == 'menyusun_hasil_meeting'"
                        >
                            Aksi
                        </th>
                    </tr>
                    <tr>
                        <th>Hasil</th>
                        <th>Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr
                        v-for="(item, idx) in renderPaginate"
                        :key="item.idx"
                        class="text-center"
                    >
                        <td>{{ idx + 1 }}</td>
                        <td>{{ item.pic }} - {{ item.divisi }}</td>
                        <td class="text-justify">{{ item.isi }}</td>
                        <td>
                            <kehadiran :kehadiran="item.hasil" />
                        </td>
                        <td>
                            <div v-if="item.dicek">
                                <span class="text-capitalize">{{
                                    changeUnderscoreToSpace(item.hasil)
                                }}</span
                                >, Oleh {{ item.dicek }},
                                {{ item.checked_at }}
                            </div>
                            <div v-else>-</div>
                        </td>
                        <td class="text-justify">
                            {{ item.catatan ?? "-" }}
                        </td>
                        <td v-if="status != 'terlaksana'">
                            <button
                                class="btn btn-outline-primary"
                                @click="kesesuaian(item)"
                                v-if="item.is_edit"
                            >
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination
                :filteredDalamProses="paginateData"
                @updateFilteredDalamProses="updatePage"
            />
        </div>
    </div>
</template>
<style>
.tbodyDragNDrop > tr {
    cursor: grab;
}
</style>