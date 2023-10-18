<script>
export default {
    methods: {
        closeModal() {
            $('.modalPilihan').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
    },
}
</script>
<template>
    <div class="modal fade modalPilihan" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilihan Cetak</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body text-center">
                    <h1>Silahkan Pilih Hasil Cetak</h1>
                    <button type="button" class="btn btn-primary btn-lg">Kertas Kecil</button>
                    <button type="button" class="btn btn-primary btn-lg">Kertas Medium</button>
                </div>
            </div>
        </div>
    </div>
</template>