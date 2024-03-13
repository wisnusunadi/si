<script>
export default {
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
            items: []
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        }
    },
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
                    <data-table :headers="headers" :items="items" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>