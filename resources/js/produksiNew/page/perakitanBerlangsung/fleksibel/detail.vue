<script>
import DataTable from '../../../components/DataTable.vue'
import modalPilihan from '../riwayat/modalPilihan.vue'
export default {
    components: {
        DataTable,
        modalPilihan
    },
    props: ['produk'],
    data() {
        return {
            search: '',
            loading: false,
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            riwayatRakit: [
                {
                    noseri: '1234567890'
                },
                {
                    noseri: '1234567890'
                }
            ],
            showModalPilihan: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        cetakBarcode() {
            this.showModalPilihan = true
            this.$nextTick(() => {
                $('.modalPilihan').modal('show')
                $('.modalDetail').modal('hide')
            })
        },
        closeModaPilihan() {
            this.showModalPilihan = false
            this.$nextTick(() => {
                $('.modalPilihan').modal('hide')
                $('.modalDetail').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <modalPilihan :data="riwayatRakit" v-if="showModalPilihan" @closeModal="closeModaPilihan" />
        <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">No BPPB</label>
                                        <div class="card-group">
                                            <div class="card" style="background-color: #C8E1A7">
                                                <div class="card-body">
                                                    <p class="card-text" id="d_rakit">{{ produk.no_bppb }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Perakitan</label>
                                        <div class="card-group">
                                            <div class="card" style="background-color: #FFECB2">
                                                <div class="card-body">
                                                    <p class="card-text" id="t_rakit">{{ dateFormat(produk.tgl_rakit) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nama Produk</label>
                                        <div class="card-group">
                                            <div class="card" style="background-color: #F89F81">
                                                <div class="card-body">
                                                    <p class="card-text" id="d_rakit">{{ produk.produk }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Jumlah Rakit</label>
                                        <div class="card-group">
                                            <div class="card" style="background-color: #FCF9C4">
                                                <div class="card-body">
                                                    <p class="card-text" id="t_rakit">{{ produk.jml }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Bagian (Peminta No Seri)</label>
                                        <div class="card-group">
                                            <div class="card" style="background-color: #FFCC83">
                                                <div class="card-body">
                                                    <p class="card-text" id="t_rakit">{{ produk.bagian }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <label for="">Tujuan (Minta No Seri)</label>
                                <div class="card-group">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text" id="t_rakit">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut eligendi fuga aliquam enim tempora accusantium, eos nemo voluptate eveniet architecto iusto voluptatem reprehenderit corporis dolore quam. Eius illo ipsa dolor.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                                    </div>
                                </div>
                                <DataTable :headers="headers" :items="riwayatRakit" :search="search" v-if="!loading" />
                                <div class="text-center" v-else>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>

                                <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <button class="btn btn-success" v-if="riwayatRakit.length > 0"
                                            @click="cetakBarcode">Cetak Barcode</button>
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
    </div>
</template>