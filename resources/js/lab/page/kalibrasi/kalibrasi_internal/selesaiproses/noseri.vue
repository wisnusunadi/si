<script>
export default {
    props: ['noseri'],
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'no_seri'
                }
            ],
            search: ''
        }
    },
    methods: {
        closeModalSeri() {
            $('.modalSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalSeri" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail No Seri</h5>
                    <button type="button" class="close" @click="closeModalSeri">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari..." />
                        </div>
                    </div>
                    <data-table :headers="headers" :items="noseri" />
                </div>
            </div>
        </div>
    </div>
</template>