<script>
import DataTable from '../../../components/DataTable.vue';
export default {
    components: {
        DataTable
    },
    props: ['dataGenerate'],
    data() {
        return {
            headers: [
                {
                    text: 'No. Seri',
                    value: 'noseri',
                    align: 'text-left',
                }
            ],
            noseri: [],
        }
    },
    methods: {
        closeModal() {
            $('.inputNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            })
        },
        addNoSeri() {
            for (let i = 0; i < this.dataGenerate.kurang; i++) {
                this.noseri.push({
                    noseri: ''
                })
            }
        },
        focusFirstInput() {
            setTimeout(() => {
                this.$refs.noseri[0].focus();
            }, 500);
        },
        keyUpperCase(e) {
            this.dataGenerate.no_bppb = e.target.value.toUpperCase()
        },
    },
    mounted() {
        this.addNoSeri();
        this.focusFirstInput();
    },
}
</script>
<template>
    <div class="modal fade inputNoSeri" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Input Nomor Seri</h5>
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
                                            <span id="jml">{{ dataGenerate.kurang_rakit }}</span>
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
                            <DataTable :headers="headers" :items="noseri">
                                <template #item.noseri="{ item }">
                                    <input type="text" ref="noseri" class="form-control" v-model="item.noseri">
                                </template>
                            </DataTable>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-secondary" @click="closeModal">Keluar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>