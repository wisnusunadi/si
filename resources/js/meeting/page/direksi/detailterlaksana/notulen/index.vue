<script>
import kehadiran from "../../../../components/kehadiran.vue";
import pagination from "../../../../components/pagination.vue";
import modal from "./modal.vue";
import Sortable from "sortablejs";
export default {
    props: ["meeting", "status"],
    components: {
        kehadiran,
        pagination,
        modal,
    },
    data() {
        return {
            search: "",
            showModal: false,
            renderPaginate: [],
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
        editNotulen(data, idx) {
            this.showModal = true;
            this.formnotulen = JSON.parse(JSON.stringify(data));
            this.formnotulen.idx = idx;
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        addNotulen() {
            this.showModal = true;
            // reset formnotulen
            this.formnotulen = {
                penanggungjawab: "",
                isi: "",
                kesesuaian: "",
                catatan: "",
            };
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        close() {
            this.showModal = false;
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
    watch: {
        meeting() {
            this.initSortable();
        },
    },
};
</script>
<template>
    <div class="card">
        <modal @refresh="$emit('refresh')" v-if="showModal" @closeModal="close" />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                </div>
                <div class="p-2" v-if="status != 'menyusun_hasil_meeting'">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                </div>
            </div>
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Penanggung Jawab</th>
                        <th rowspan="2">Uraian</th>
                        <th colspan="2">Kesesuian</th>
                        <th rowspan="2">Catatan</th>
                    </tr>
                    <tr>
                        <th>Hasil</th>
                        <th>Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="(item, idx) in renderPaginate" :key="item.isi" class="text-center">
                        <td>{{ idx + 1 }}</td>
                        <td>
                            {{ item.pic }} - {{ item.jabatan }}
                        </td>
                        <td>{{ item.isi }}</td>
                        <td>
                            <kehadiran :kehadiran="item.hasil" />
                        </td>
                        <td>
                            <div v-if="item.dicek">
                                <span class="text-capitalize">{{ changeUnderscoreToSpace(item.hasil) }}</span>, Oleh
                                {{ item.dicek }},
                                {{ item.checked_at }}
                            </div>
                            <div v-else>
                                -
                            </div>
                        </td>
                        <td>
                            {{ item.catatan ?? "-" }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination :filteredDalamProses="paginateData" @updateFilteredDalamProses="updatePage"/>
        </div>
    </div>
</template>
<style>
.tbodyDragNDrop > tr {
    cursor: grab;
}
</style>