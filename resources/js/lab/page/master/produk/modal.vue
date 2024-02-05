<script>
import axios from 'axios';
export default {
    props: ["produk"],
    data() {
        return {
            alat: [],
        }
    },
    methods: {
        closeModal() {
            $(".modalProduk").modal("hide");
            this.$emit("closeModal");
        },
        async simpan() {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil disimpan.', 'success')
                this.$emit("refresh")
                this.closeModal()
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal disimpan.', 'error')
            }

            // check null value
            if (this.produk.kode_lab == null || this.produk.kode_lab == '') {
                this.$swal('Gagal!', 'Data gagal disimpan. Alat tidak boleh kosong.', 'error')
                return
            }

            const { data } = await axios.put(`/api/labs/produk/${this.produk.id}`, {
                kode_lab_id: this.produk.kode_lab.id,
            }).then(success).catch(error)
            this.closeModal()
        },
        async getAlat() {
            try {
                const { data } = await axios.get("/api/labs/kode")
                this.alat = data.data.map((data) => {
                    return {
                        id: data.id,
                        label: data.nama,
                    };
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.getAlat();
    }
}
</script>
<template>
    <div class="modal fade modalProduk" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Alat Produk {{ produk.nama }}</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                      <label for="" class="col-2">Nama Alat</label>
                      <v-select :options="alat" class="col-10" v-model="produk.kode_lab"
                      ></v-select>
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