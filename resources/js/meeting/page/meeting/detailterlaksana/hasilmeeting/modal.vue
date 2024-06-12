<script>
import axios from 'axios';
export default {
    props: ['formhasilmeeting'],
    data() {
        return {
            isi: JSON.parse(JSON.stringify(this.formhasilmeeting.isi)) ?? "",
        }
    },
    methods: {
        save() {
            const form = {
                meeting_id: this.$route.params.id,
                id: this.formhasilmeeting.id ?? null,
                isi: this.isi,
            }
            axios.post("/api/hr/meet/hasil", form)
                .then((res) => {
                    this.$emit("refresh");
                    this.closeModal();
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        closeModal() {
            $(".modalHasilMeeting").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalHasilMeeting"
        id="modelId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Hasil Meeting</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Hasil Rapat</label>
                        <textarea
                            class="form-control"
                            v-model="isi"
                        ></textarea>
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
                    <button type="button" class="btn btn-primary" @click="save">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>