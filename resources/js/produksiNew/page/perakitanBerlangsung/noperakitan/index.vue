<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
import modalPilihan from '../perakitan/modalPilihan.vue'
import Seriviatext from '../../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue'
import moment from 'moment'
export default {
    data(){
        return {
            form:{
                alias:'',
                urutan_awal:'',
                urutan_akhiir:''
            }
        }
    },
    methods: {
  async simpan() {
    try {
      const { alias, urutan_awal, urutan_akhir } = this.form;

      if (!alias || !urutan_awal || !urutan_akhir) {
        this.$swal(
                    "Peringatan",
                    "Form Kosong",
                    "warning"
                );
                return;
      }
      if (alias.length !== 2) {
        this.$swal(
                    "Peringatan",
                    "Maksimal 2 Karakter",
                    "warning"
                );
                return;
      }



         // Check if urutan_awal and urutan_akhir are within the range of 1 to 9999
         if (parseInt(urutan_awal) < 1 || parseInt(urutan_awal) > 9999 || parseInt(urutan_akhir) < 1 || parseInt(urutan_akhir) > 9999) {
            this.$swal(
                    "Peringatan",
                    "Perhatikan Urutan",
                    "warning"
                );
                return;
      }

      if (parseInt(urutan_awal) > parseInt(urutan_akhir) || urutan_awal == urutan_akhir ) {
        this.$swal(
                    "Peringatan",
                    "Cek Urutan Kembali",
                    "warning"
                );
                return;
      }


      const response = await axios.get(`/produksiReworks/cetak_seri_perakitan_custom_a4/${alias}/${urutan_awal}/${urutan_akhir}`, {
        responseType: 'blob'
      });


      const blob = new Blob([response.data], { type: 'application/pdf' });
      const url = window.URL.createObjectURL(blob);


      window.open(url, '_blank');
    } catch (error) {
      console.log(error);
    }
  }
}
}
</script>
<template>
    <div>
        <div class="modal-body">
                        <div class="card">
                            <div class="card-body" >
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Nama Alias</label>
                                            <input type="text"   v-model="form.alias" class="form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="">No Urut Awal</label>
                                                    <input type="number"    v-model="form.urutan_awal"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="">No Urut Akhir</label>
                                                    <input type="number"     v-model="form.urutan_akhir" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 bd-highlight">
                                <button class="btn btn-success" @click="simpan" >Cetak</button>
                            </div>
                        </div>
                    </div>
    </div>
</template>
