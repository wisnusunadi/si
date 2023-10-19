<script>
export default {
    props: ['dataGenerate'],
    data() {
        return {
            showModal: false,
            form: {
                kedatangan: 0,
                jml_noseri: 0,
                no_urut_terakhir: 0,
            },
            loading: false,
        }
    },
    methods: {
        keyUpperCase(e) {
            e.target.value = e.target.value.toUpperCase();
        },
        closeModal() {
            $('.modalGenerate').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        simpan() {
            const ceknotnull = Object.values(this.form).every(x => x !== null && x !== '' && x !== 0)
            const cekbppb = this.dataGenerate.no_bppb !== null && this.dataGenerate.no_bppb !== '' && this.dataGenerate.no_bppb !== '-'
            if (ceknotnull && cekbppb && this.jumlahRakit) {
                this.loading = true
                const kirim = {
                    ...this.dataGenerate,
                    ...this.form,
                }
                this.$emit('generate', data)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan cek kembali form anda',
                })
            }
        }
    },
    computed: {
        jumlahRakit() {
            return this.dataGenerate.jumlah > this.form.jml_noseri ? true : false
        },
    },
    watch: {
        'form.kedatangan': function (val) {
            if (val > 26) {
                this.form.kedatangan = 26
            } else if (val < 0) {
                this.form.kedatangan = 0
            } else {
                this.form.kedatangan = val
            }
        },
    }
}
</script>
<template>
    <div class="modal fade modalGenerate" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Generate Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nomor BPPB</label>

                                    <div class="card">
                                        <div class="card-body">
                                            <input type="text" name="no_bppb" id="no_bppb" class="form-control"
                                                v-model="dataGenerate.no_bppb" @keyup="keyUpperCase($event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="produk">{{ dataGenerate.nama_produk }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Kategori</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body">
                                            <span id="kategori">{{ dataGenerate.kategori }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Jumlah Rakit</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body">
                                            <span id="jml">{{ dataGenerate.jumlah_unit }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="start">{{ dataGenerate.tgl_mulai }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="end">{{ dataGenerate.tgl_selesai }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kedatangan</label>
                                    <input type="text" class="form-control" v-model.number="form.kedatangan"
                                        @keypress="numberOnly($event)">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Jumlah Noseri yang dibuat</label>
                                    <input type="text" class="form-control" :class="jumlahRakit ? 'is-valid' : 'is-invalid'"
                                        @keypress="numberOnly($event)" v-model.number="form.jml_noseri">
                                    <div class="invalid-feedback" v-if="!jumlahRakit">
                                        Jumlah Noseri yang dibuat tidak boleh lebih dari jumlah rakit
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">No Urut Terakhir</label>
                                    <input type="text" class="form-control" @keypress="numberOnly($event)"
                                        v-model.number="form.no_urut_terakhir">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-success" @click="simpan">Generate</button>
                </div>
            </div>
        </div>
</div></template>