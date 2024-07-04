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
        initSortable() {
            let table = document.querySelector(".tbodyDragNDrop");
            const _self = this;
            // this way we avoid data binding

            if (this.status == "menyusun_hasil_meeting") {
                Sortable.create(table, {
                    onEnd({ newIndex, oldIndex }) {
                        _self.meeting.splice(
                            newIndex,
                            0,
                            ..._self.meeting.splice(oldIndex, 1)
                        );
                    },
                });
            }
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
    mounted() {
        this.initSortable();
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
        <modal
            @refresh="$emit('refresh')"
            v-if="showModal"
            @closeModal="close"
        />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <button
                        class="btn btn-primary"
                        @click="addNotulen"
                        v-if="status == 'menyusun_hasil_meeting'"
                    >
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
                <div class="p-2" v-if="status != 'menyusun_hasil_meeting'">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Penanggung Jawab</th>
                            <th rowspan="2" style="width: 600px">Uraian</th>
                            <th colspan="2">Kesesuian</th>
                            <th rowspan="2" style="width: 500px">Catatan</th>
                        </tr>
                        <tr>
                            <th>Hasil</th>
                            <th>Dicek Oleh</th>
                        </tr>
                    </thead>
                    <tbody
                        class="tbodyDragNDrop text-center"
                        v-if="status == 'menyusun_hasil_meeting'"
                    >
                        <tr v-for="(item, idx) in meeting" :key="idx">
                            <td>{{ idx + 1 }}</td>
                            <td>{{ item.pic }} - {{ item.jabatan }}</td>
                            <td class="text-justify text-wrap">{{ item.isi }}</td>
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
                            <td class="text-justify text-wrap">
                                {{ item.catatan ?? "-" }}
                            </td>
                            <!-- <td>
                            <button class="btn btn-outline-warning" @click="editNotulen(item, idx)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" @click="meeting.splice(idx, 1)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td> -->
                        </tr>
                    </tbody>
                    <tbody v-if="status != 'menyusun_hasil_meeting'">
                        <tr
                            v-for="(item, idx) in renderPaginate"
                            :key="idx"
                            class="text-center"
                        >
                            <td>{{ idx + 1 }}</td>
                            <td>{{ item.pic }} - {{ item.jabatan }}</td>
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
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :filteredDalamProses="paginateData"
                @updateFilteredDalamProses="updatePage"
                v-if="status != 'menyusun_hasil_meeting'"
            />
        </div>
    </div>
</template>
<style>
.tbodyDragNDrop > tr {
    cursor: grab;
}
</style>
