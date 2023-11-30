<script>
import axios from 'axios';
export default {
    data() {
        return {
            form: {
                provinsi: '',
                kota: '',
                jumlah: '',
            },
            provinsi: [],
            kota: [],
            jumlahMaksKirim: 8000,
        }
    },
    methods: {
        closeModal() {
            $('.modalPembagian').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        getProvinsi() {
            try {
                axios.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then((response) => {
                    this.provinsi = response.data.map((data) => {
                        return {
                            label: data.name,
                            value: data.id,
                        }
                    });
                });
                
            } catch (error) {
                console.log(error);
            }
        },
        getKota() {
            try {
                let id = this.form.provinsi?.value;
                axios.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`).then((response) => {
                    this.kota = response.data.map((data) => {
                        return {
                            label: data.name,
                            value: data.id,
                        }
                    });
                });
                
            } catch (error) {
                console.log(error);
            }
        },
        kirim() {
            const cekNull = Object.values(this.form).every((data) => data !== '' && data !== null);

            if (cekNull) {
                console.log(this.form);
                // this.$emit('kirim', this.form);
                this.closeModal();
                this.$swal('Berhasil', 'Data berhasil ditambahkan', 'success');
            } else {
                this.$swal('Error', 'Data tidak boleh kosong', 'error')
            }
        }
    },
    mounted() {
        this.getProvinsi();
    }
}
</script>
<template>
    <div class="modal fade modalPembagian" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pembagian Wilayah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="">Provinsi</label>
                        <v-select :options="provinsi" v-model="form.provinsi" @input="getKota" />
                    </div>
                    <div class="form-group">
                      <label for="">Kab. / Kota</label>
                        <v-select v-model="form.kota" :options="kota" />
                    </div>
                    <div class="form-group">
                      <label for="">Jumlah Pengiriman</label>
                        <input type="text" class="form-control"
                        :class="{'is-invalid': form.jumlah > jumlahMaksKirim}"
                        v-model="form.jumlah"
                        placeholder="Jumlah Pengiriman" @keypress="numberOnly($event)">
                              <div id="validationServer04Feedback" class="invalid-feedback">
            Jumlah pengiriman tidak boleh lebih dari {{ jumlahMaksKirim  }}
          </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="kirim">Simpan</button>
                </div>

            </div>
        </div>
    </div>
</template>