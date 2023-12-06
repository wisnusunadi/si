<script>
import axios from 'axios';
export default {
    props: ['data'],
    data() {
        return {
            alasan: '',
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
            let cetak = JSON.stringify(this.data);
            window.open(`/produksiReworks/cetak_seri_fg_small?data=${cetak}`, '_blank')
            this.postAlasan();
            this.closeModal();
        },
        medium() {
            let cetak = JSON.stringify(this.data);
            window.open(`/produksiReworks/cetak_seri_fg_medium?data=${cetak}`, '_blank')
            this.postAlasan();
            this.closeModal();
        },
        postAlasan() {
            let form = {
                alasan: this.alasan,
                data: this.data
            }

            try {
                axios.post('/api/prd/fg/riwayat_code', form, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                    }
                })
            } catch (error) {
                console.log(error);
            } finally {
                // this.closeModal();
            }
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
                        <label for="">Alasan Cetak</label>
                        <textarea class="form-control" name="" id="" rows="3" v-model="alasan"></textarea>
                    </div>
                    <div class="text-center">
                        <h1>Silahkan Pilih Hasil Cetak</h1>
                        <button type="button" class="btn btn-primary btn-lg" @click="small">Kertas Kecil</button>
                        <button type="button" class="btn btn-primary btn-lg" @click="medium">Kertas Medium</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>