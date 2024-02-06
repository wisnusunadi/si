<script>
import axios from 'axios'
export default {
    props: ['data'],
    data() {
        return {
            file: null,
            hasilPreview: null,
            loading: false,
            error: false,
            noseri: null,
            loadingUpload: false
        }
    },
    methods: {
        closeModal() {
            $('.modalUnggah').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async preview() {
            if (this.file) {
                try {
                    this.loading = true
                    let formData = new FormData()
                    formData.append('file_csv', this.file)
                    formData.append('soid1', this.data.id)
                    const { data } = await axios.post('/api/v2/gbj/preview-so', formData)
                    this.hasilPreview = data.data
                    this.error = false
                } catch (error) {
                    this.error = true
                    const { msg, data, noseri } = error.response.data
                    if (data) {
                        this.hasilPreview = data
                    }
                    if (noseri) {
                        this.noseri = noseri
                    }

                    swal.fire('Error', msg, 'error')
                } finally {
                    this.loading = false
                }
            }
        },
        async sendData() {
            try {
                this.loadingUpload = true
                const formData = new FormData()
                formData.append('soid', this.data.id)
                formData.append('file_csv', this.file)
                const { data } = await axios.post('/api/v2/gbj/store-sodb', formData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })

                console.log(data)
            } catch (error) {
                console.error(error)
            } finally {
                this.loadingUpload = false
            }
        }
    }
}
</script>
<template>
    <div class="modal fade modalUnggah" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unggah File</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Sales Order File</label>
                        <input type="file" class="form-control" @change="file = $event.target.files[0]">
                    </div>
                    <button class="btn-sm btn btn-info" @click="preview" :disabled="loading">
                        <i class="fas fa-eye" v-if="!loading"></i>
                        <div class="spinner-border spinner-border-sm" role="status" v-else>
                            <span class="sr-only">Loading...</span>
                        </div>
                        {{ loading ? 'Loading...' : 'Preview' }}
                    </button>

                    <div class="card" v-if="hasilPreview">
                        <div class="card-body">
                            <div v-html="hasilPreview"></div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    No Seri yang belum terdaftar: {{ noseri }}
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-danger btn-sm" :disabled="error || loadingUpload"
                                        @click="sendData">
                                        <i class="fas fa-upload" v-if="!loadingUpload"></i>
                                        <div class="spinner-border spinner-border-sm" role="status" v-else>
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        {{ loadingUpload ? 'Loading...' : 'Upload' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>