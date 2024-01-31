<script>
import axios from 'axios';
export default {
    props: ['data'],
    data() {
        return {
            alasan: '',
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
            if (this.alasan == '') {
                this.$swal.fire('Perhatian', 'Alasan Cetak Harus Diisi', 'warning');
                return;
            }

            let cetak = JSON.stringify(this.data);
            window.open(`/produksiReworks/cetak_seri_fg_small_repeated_nonstok?data=${cetak}&merk=${this.merkSelected}`, '_blank')
            this.postAlasan();
            this.closeModal();
        },
        medium() {
            if (this.alasan == '') {
                this.$swal.fire('Perhatian', 'Alasan Cetak Harus Diisi', 'warning');
                return;
            }

            let cetak = JSON.stringify(this.data);
            window.open(`/produksiReworks/cetak_seri_fg_medium_repeated_nonstok?data=${cetak}&merk=${this.merkSelected}`, '_blank')
            this.postAlasan();
            this.closeModal();
        },
        postAlasan() {
            let form = {
                alasan: this.alasan,
                data: this.data
            }

            try {
                axios.post('/api/prd/fg/non_stok/riwayat', form, {
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
                    <div class="form-group">
                        <label for="">Merk</label>
                        <v-select :options="merk" :reduce="option => option.value" v-model="merkSelected"
                            placeholder="Pilih Merk" />
                        <small class="text-muted"><span class="text-danger">*</span>Merk akan tampil jika cetak kertas medium</small>
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