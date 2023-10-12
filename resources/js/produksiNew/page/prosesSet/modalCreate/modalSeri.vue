<script>
import barcode from 'vue-barcode';
export default {
    props: ['hasilGenerate'],
    components: {
        barcode,
    },
    methods: {
        closeModal() {
            $('.modalSeri').modal('hide');
        }
    },
}
</script>
<template>
    <div class="modal fade modalSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- generate seri with barcode -->
                    <div class="d-flex justify-content-center">
                        <barcode :text="hasilGenerate">
                            Show this if the rendering fails.
                        </barcode>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</template>