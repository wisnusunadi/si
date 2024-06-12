<script>
import dokumens from "../../../components/dokumen.vue";
export default {
    props: ["dokumen", "id"],
    components: {
        dokumens,
    },
    data() {
        return {
            pilihFile: [],
        };
    },
    methods: {
        closeModal() {
            $(".modalSelectLampiran").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        checkedAll() {
            if (this.mapAllIds.length === this.pilihFile.length) {
                this.pilihFile = [];
            } else {
                const maps = JSON.parse(JSON.stringify(this.mapAllIds));
                this.pilihFile = maps;
            }
        },
        checkOne(id) {
            if (this.pilihFile.includes(id)) {
                this.pilihFile.splice(this.pilihFile.indexOf(id), 1);
            } else {
                this.pilihFile.push(id);
            }
        },
        cetakHasilMeeting() {
            // remove same id
            let id = this.$route.params.id || this.id;
            let pilihFile = [...new Set(this.pilihFile)];
            console.log("pilihFile", pilihFile);
            console.log("id", id);
            window.open(
                `/pdfmeet/hasil/${
                    id
                }?dokumen=${pilihFile.join(",")}`,
                "_blank"
            );
        },
    },
    computed: {
        mapAllIds() {
            const ids = [];

            const dokumen = JSON.parse(JSON.stringify(this.dokumen));

            dokumen.forEach((meet) => {
                meet.dokumen.forEach((dokumen) => {
                    ids.push(dokumen.id);
                });
            });

            return ids;
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalSelectLampiran"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-xl modal-dialog-scrollable"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Lampiran</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            :checked="pilihFile.length === mapAllIds.length"
                            @click="checkedAll"
                        />
                        <label class="form-check-label" for="defaultCheck1">
                            Pilih Semua
                        </label>
                    </div>
                    <div class="accordion" id="accordion">
                        <div class="card" v-for="(meet, idx) in dokumen">
                            <div class="card-header" :id="'heading' + idx">
                                <h5 class="mb-0">
                                    <button
                                        class="btn btn-link"
                                        data-toggle="collapse"
                                        :data-target="'#collapseOne' + idx"
                                        aria-expanded="true"
                                        :aria-controls="'collapseOne' + idx"
                                    >
                                        {{ meet.jenis }} ({{
                                            meet.dokumen.length
                                        }}
                                        file(s))
                                    </button>
                                </h5>
                            </div>

                            <div
                                :id="'collapseOne' + idx"
                                class="collapse"
                                :class="{ show: idx == 0 }"
                                :aria-labelledby="'heading' + idx"
                                data-parent="#accordion"
                            >
                                <div class="card-body">
                                    <div
                                        class="row"
                                        v-if="meet.dokumen.length > 0"
                                    >
                                        <div
                                            v-for="dokumen in meet.dokumen"
                                            class="col-5"
                                        >
                                            <div class="d-flex bd-highlight">
                                                <div
                                                    class="p-2 flex-fill bd-highlight"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        :checked="pilihFile && pilihFile.includes(dokumen.id)"
                                                        @click="checkOne(dokumen.id)"
                                                    />
                                                </div>
                                                <div
                                                    class="p-2 flex-fill bd-highlight"
                                                >
                                                    <dokumens
                                                        :jenis="meet.jenis"
                                                        :nama="dokumen.nama"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p class="text-center">
                                            Tidak ada file
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        @click="cetakHasilMeeting"
                    >
                        <i class="fa fa-print"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>