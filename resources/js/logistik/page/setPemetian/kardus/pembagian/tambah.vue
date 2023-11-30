<script>
import axios from 'axios';
export default {
    data() {
        return {
            form: {
                provinsi: '',
                kota: '',
            },
            provinsi: [],
            kota: [],
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
        }
    },
    mounted() {
        this.getProvinsi();
    }
}
</script>
<template>
    <div class="modal fade modalPembagian" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                        <v-select :options="kota" />
                    </div>
                    <div class="form-group">
                      <label for="">Jumlah Pengiriman</label>
                    <input type="text" class="form-control" placeholder="Jumlah Pengiriman" @keypress="numberOnly($event)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
                
            </div>
        </div>
    </div>
</template>