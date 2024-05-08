<script>
import axios from 'axios'
export default {
    data() {
        return {
            form: {
                alias: '',
                urutan_awal: '',
                urutan_akhir: ''
            }
        }
    },
    methods: {
        async simpan() {
            try {
                const cekKosong = Object.values(this.form).every((val) => val !== '');
                if (!cekKosong) {
                    this.$swal(
                        "Peringatan",
                        "Data Tidak Boleh Kosong",
                        "warning"
                    );
                    return;
                }


                if (this.form.alias.length !== 2) {
                    this.$swal(
                        "Peringatan",
                        "Maksimal 2 Karakter",
                        "warning"
                    );
                    return;
                }

                if (parseInt(this.form.urutan_awal) < 1 || parseInt(this.form.urutan_awal) > 9999 || parseInt(this.form.urutan_akhir) < 1 || parseInt(this.form.urutan_akhir) > 9999) {
                    this.$swal(
                        "Peringatan",
                        "Cek Urutan Kembali",
                        "warning"
                    );
                    return;
                }

                if (parseInt(this.form.urutan_akhir) > parseInt(this.form.urutan_akhir) || this.form.urutan_awal == this.form.urutan_akhir) {
                    this.$swal(
                        "Peringatan",
                        "Cek Urutan Kembali",
                        "warning"
                    );
                    return;
                }

                if (!/^[a-zA-Z]+$/.test(this.form.alias)) {
                    this.$swal(
                        "Peringatan",
                        "Harus Mengandung Karakter",
                        "warning"
                    );
                    return;
                }
                window.open(`/produksiReworks/cetak_seri_perakitan_custom_a4/${this.form.alias}/${this.form.urutan_awal}/${this.form.urutan_akhir}`, '_blank');
            } catch (error) {
                console.log(error);
            }
        }
    },
    watch: {
        form: {
            handler() {
                // Mengubah alias menjadi huruf kapital
                this.form.alias = this.form.alias.toUpperCase();
                // Mengubah urutan_awal menjadi angka
                this.form.urutan_awal = parseInt(this.form.urutan_awal);
                // Mengubah urutan_akhir menjadi angka
                this.form.urutan_akhir = parseInt(this.form.urutan_akhir);
                // jika urutan_awal lebih besar dari urutan_akhir maka urutan_akhir akan diubah menjadi urutan_awal
                if (this.form.urutan_awal > this.form.urutan_akhir) {
                    this.form.urutan_akhir = this.form.urutan_awal;
                }
                // urutan awal dan urutan akhir tidak boleh kuran dari 1 dan tidak boleh lebih dari 9999
                if (this.form.urutan_awal < 1) {
                    this.form.urutan_awal = 1;
                }
                if (this.form.urutan_awal > 9999) {
                    this.form.urutan_awal = 9999;
                }
                if (this.form.urutan_akhir < 1) {
                    this.form.urutan_akhir = 1;
                }
                if (this.form.urutan_akhir > 9999) {
                    this.form.urutan_akhir = 9999;
                }
            },
            deep: true
        }
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="" class="col-lg-5 text-right">Nama Alias</label>
                <input type="text" v-model="form.alias" class="form-control col-lg-2">
            </div>
            <div class="form-group row">
                <label for="" class="col-lg-5 text-right">No Urut Awal</label>
                <input type="number" v-model.number="form.urutan_awal" @keypress="numberOnly($event)"
                    class="form-control col-lg-2">
            </div>
            <div class="form-group row">
                <label for="" class="col-lg-5 text-right">No Urut Akhir</label>
                <input type="number" v-model.number="form.urutan_akhir" @keypress="numberOnly($event)"
                    class="form-control col-lg-2">
            </div>
            <div class="row">
                <div class="col-lg-5">
                </div>
                <div class="col-lg-2 text-right">
                    <!-- Mengubah kolom dari col menjadi col-lg-5 dan menambahkan kelas text-right -->
                    <button class="btn btn-success" @click="simpan">Cetak</button>
                </div>
            </div>
        </div>
    </div>

</template>
