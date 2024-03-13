<script>
export default {
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb',
                },
                {
                    text: 'Jenis Perakitan',
                    value: 'jenis_perakitan',
                },
                {
                    text: 'Tanggal Rakit',
                    value: 'tgl_rakit',
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tgl_transfer',
                }
            ],
            items: [],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('#modelId').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        }
    },
}
</script>
<template>
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="items" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>