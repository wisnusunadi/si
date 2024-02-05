<script>
import pagination from "../../../../components/pagination.vue";
export default {
    data() {
        return {
            search: "",
            renderPaginate: [],
        };
    },
    components: {
        pagination,
    },
    props: ["formDetailSeri"],
    methods: {
        closeModal() {
            $(".modalNoSeri").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filteredDalamProses() {
            return this.formDetailSeri.filter((item) => {
                return Object.keys(item).some((key) => {
                    return String(item[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalNoSeri"
        id="exampleModal" data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Cari"
                                v-model="search"
                            />
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No Seri</th>
                                <th>Waktu Pakai</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr
                                v-for="(item, index) in renderPaginate"
                                :key="index"
                            >
                                <td>{{ item.noseri }}</td>
                                <td>{{ formatDate(item.waktu_pakai) }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="2" class="text-center">
                                    Data Kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination
                        :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
