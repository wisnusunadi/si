<script>
import axios from 'axios';

export default {
    props: ['detail'],
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'noseri'
                },
                {
                    text: 'Tanggal Buat',
                    value: 'tglbuat'
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tgltransfer'
                }
            ],
            items: [],
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        async getData() {
            try {
                this.loading = true
                const { data } = await axios.get(`/api/prd/fg/seri_bppb/${this.detail.id}`)
                this.items = data.map(item => {
                    return {
                        noseri: item.noseri,
                        tglbuat: this.dateFormat(item.tgl_buat),
                        tgltransfer: this.dateFormat(item.tgl_tf)
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="modal fade modalNoSeri" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                        </div>
                    </div>
                    <data-table :headers="headers" :items="items" :search="search" v-if="!loading" />
                    <div v-else class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>