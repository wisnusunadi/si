<script>
export default {
    data() {
        return {
            noseri: [],
            loadingNoSeri: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalcetak').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        removeSeri(index) {
            this.noseri.splice(index, 1);
        },
        autoTab(event, idx) {
            event.target.value = event.target.value.toUpperCase();
            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            });
            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseriUnique = [...new Set(noseri.map((item) => item.noseri))];

            if (noseri.length !== noseriUnique.length) {
                this.noseri[idx].error = true;
                this.noseri[idx].message = 'No. Seri tidak boleh sama';
                this.$swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No. Seri tidak boleh sama',
                    timer: 1000,
                    showConfirmButton: false,
                });
                this.loadingNoSeri = true;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                    setTimeout(() => {
                        this.$refs.noseri[idx].focus();
                    }, 100);
                });
                return
            } else {
                this.loadingNoSeri = true;
                delete this.noseri[idx].error;
            }
        }
    },
}
</script>
<template>
    <div>
        <div class="modal fade modalcetak" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Tambah Nomor Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Keperluan</label>
                                    <textarea cols="5" class="form-control"></textarea>
                                </div>
                                <div class="d-flex bd-highlight mb-3">
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-primary">Input No Seri Via Text</button>
                                    </div>
                                    <div class="ml-auto p-2 bd-highlight">
                                        <button class="btn btn-info" @click="noseri.push({ noseri: '' })">Tambah No.
                                            Seri</button>
                                    </div>
                                </div>
                                <div class="scrollable">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No. Seri</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(seri, index) in noseri" :key="index">
                                                <td>
                                                    <input type="text" class="form-control" v-model="seri.noseri"
                                                        ref="noseri"
                                                        :class="seri.error ? 'is-invalid' : ''"
                                                        @keyup.enter="autoTab($event, index)"
                                                        @keyup="$event.target.value = $event.target.value.toUpperCase()">
                                                    <div class="invalid-feedback" v-if="seri.error">
                                                        {{ seri.message }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-outline-danger" @click="removeSeri(index)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
