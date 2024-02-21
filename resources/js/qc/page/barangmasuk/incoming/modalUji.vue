<script>
import noseri from './modalNoSeri.vue'
export default {
    components: {
        noseri
    },
    props: ['produk'],
    data() {
        return {
            maxDate: new Date().toISOString().split('T')[0],
            headers: [
                {
                    text: 'Tgl Terima',
                    value: 'tgl_terima'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Jml No Seri Dipilih',
                    value: 'noseri',
                },
                {
                    text: 'Progress',
                    value: 'progress',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                },
            ],
            penerimaan: [
                {
                    id: 1,
                    tgl_terima: '10 Oktober 2023', // dari pengiriman produksi
                    jumlah: 50,
                    lolos: 10,
                    tidak_lolos: 5,
                    persentase_lolos: 20,
                    persentase_tidak_lolos: 10,
                }
            ],
            search: '',
            dataSelected: [],
            showModal: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalUji').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        openModalNoSeri(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data))
            this.showModal = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
                $('.modalUji').modal('hide')
            })
        },
        closeModalNoSeri() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalUji').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <noseri v-if="showModal" :produk="dataSelected" @closeModal="closeModalNoSeri" />
        <div class="modal fade modalUji" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrolllable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="card">
                                            <div class="card-header">Info Produk</div>
                                            <div class="card-body text-center">
                                                <b>{{ produk.produk }}</b>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">Info Perakitan</div>
                                            <div class="card-body">
                                                <div class="margin">
                                                    <div><small class="text-muted">Nomor BPPB</small></div>
                                                    <div><b>{{ produk.bppb }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Jumlah Rakit</small></div>
                                                    <div><b>{{ produk.jumlah }} Unit</b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5 text-right">Tanggal
                                                        Uji</label>
                                                    <div class="col-5">
                                                        <input type="date" :max="maxDate" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5 text-right">Waktu
                                                        Uji</label>
                                                    <div class="col-5">
                                                        <time-picker></time-picker>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-5"
                                                        style="text-align: right">Hasil
                                                        Cek</label>
                                                    <div class="col-7 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cek" id="yes"
                                                                value="ok">
                                                            <label class="form-check-label" for="yes"><i
                                                                    class="fas fa-check-circle text-success"></i> OK</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cek" id="no"
                                                                value="nok">
                                                            <label class="form-check-label" for="no"><i
                                                                    class="fas fa-times-circle text-danger"></i> Tidak
                                                                OK</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-row-reverse bd-highlight">
                                                    <div class="p-2 bd-highlight">
                                                        <input type="text" class="form-control" v-model="search"
                                                            placeholder="Cari...">
                                                    </div>
                                                </div>
                                                <data-table :headers="headers" :items="penerimaan" :search="search">
                                                    <template #item.noseri="{ item }">
                                                        {{ item?.noseri?.length ?? 0 }}
                                                    </template>
                                                    <template #item.progress="{ item }">
                                                        <!-- baru -->
                                                        <span class="badge badge-success">Lolos: {{ item.lolos }} Unit {{
                                                            item.persentase_lolos }}%</span> <br>
                                                        <span class="badge badge-danger">Tidak Lolos: {{ item.tidak_lolos }}
                                                            Unit {{ item.persentase_tidak_lolos
                                                            }}%</span>
                                                    </template>
                                                    <template #item.aksi="{ item }">
                                                        <button class="btn btn-primary btn-sm"
                                                            @click="openModalNoSeri(item)">
                                                            <i class="fas fa-qrcode"></i>
                                                            Pilih No Seri
                                                        </button>
                                                    </template>
                                                </data-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>