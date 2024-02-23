<script>
import axios from 'axios';
export default {
    props: ['header'],
    data() {
        return {
            pemilik: JSON.parse(JSON.stringify(this.header.jenis_pemilik)),
            optionPemilik: [],
        }
    },
    methods: {
        closeModal() {
            $(".modalEditPemilik").modal("hide");
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        async getData() {
            try {
                const { data: kepemilikan } = await axios.get("/api/labs/kode_milik").then(res => res.data)
                this.optionPemilik = kepemilikan.map((data) => {
                    return {
                        value: data.id,
                        label: data.nama,
                    };
                });
            } catch (error) {
                console.log(error);
            }
        },
        async simpan() {
            try {
                const { data } = await axios.post('/api/labs/ubah_jenis_pemilik', {
                    id: this.header.id,
                    pemilik: this.pemilik
                })
                this.$swal('Berhasil!', 'Data berhasil disimpan.', 'success')
                this.closeModal()
                this.$emit('refresh')
            } catch (error) {
                console.log(error)
                this.$swal('Gagal!', 'Data gagal disimpan.', 'error')
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="modal fade modalEditPemilik" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pemilik</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Pemilik</label>
                        <v-select v-model="pemilik" :options="optionPemilik"></v-select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.modal-body {
    min-height: 25vh;
    overflow-y: auto;
}
</style>