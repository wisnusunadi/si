<script>
export default {
    props: ['data'],
    data() {
        return {
            merk: [
                { value: 'elitech', label: 'Elitech' },
                { value: 'vanward', label: 'Vanward' },
                { value: 'rgb', label: 'RGB' },
                { value: 'mentor', label: 'Mentor' },
                { value: 'tanpa_merk', label: 'Tanpa Merk' }
            ],
            merkSelected: 'elitech',
        }
    },
    methods: {
        closeModal() {
            $('.modalPilihan').modal('hide');
            this.alasan = '';
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        small() {
            window.open(`/produksiReworks/cetak_seri_fg_small?id=${this.data}&merk=${this.merkSelected}`, '_blank')
            this.closeModal();
            this.$emit('closeAllModal')
        },
        medium() {
            window.open(`/produksiReworks/cetak_seri_fg_medium?id=${this.data}&merk=${this.merkSelected}`, '_blank')
            this.closeModal();
            this.$emit('closeAllModal')
        },
        big() {
            window.open(`/produksiReworks/cetak_seri_fg_big?id=${this.data}&merk=${this.merkSelected}`, '_blank')
            this.closeModal();
            this.$emit('closeAllModal')
        }
    },
}
</script>
<template>
    <div class="modal fade modalPilihan" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilihan Cetak</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Merk</label>
                        <v-select :options="merk" :reduce="option => option.value" v-model="merkSelected"
                            placeholder="Pilih Merk" />
                        <small class="text-muted"><span class="text-danger">*</span>Merk akan tampil jika cetak kertas
                            medium</small>
                    </div>
                    <div class="text-center">
                        <h1>Silahkan Pilih Hasil Cetak</h1>
                        <button type="button" class="btn btn-primary btn-lg" @click="small">Kertas Kecil</button>
                        <button type="button" class="btn btn-primary btn-lg" @click="medium">Kertas Medium</button>
                        <!-- <button type="button" class="btn btn-primary btn-lg" @click="big">Kertas A4</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
