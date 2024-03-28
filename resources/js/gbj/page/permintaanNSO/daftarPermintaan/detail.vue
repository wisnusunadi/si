<script>
export default {
    props: ['detail'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                }
            ],
            items: [
                {
                    no: 1,
                    nama_produk: 'Produk 1',
                    jumlah: 10
                },
                {
                    no: 2,
                    nama_produk: 'Produk 2',
                    jumlah: 20
                },
                {
                    no: 3,
                    nama_produk: 'Produk 3',
                    jumlah: 30
                }
            ],
            search: ''
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scorllable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Daftar Permintaan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    <div class="col"><label for="">Nomor Permintaan</label>
                                        <div class="card nomor-so">
                                            <div class="card-body"><span id="so">{{ detail.no_permintaan }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Nama Bagian</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body"><span id="akn">{{ detail.nama_bagian }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" :class="detail.jenis == 'Peminjaman' ? 'row-cols-3' : 'row-cols-2'">
                                    <div class="col">
                                        <label for="">Tanggal Kebutuhan</label>
                                        <div class="card nomor-po">
                                            <div class="card-body"><span id="po">{{ detail.tgl_kebutuhan }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col"><label for="">Jenis</label>
                                        <div class="card instansi">
                                            <div class="card-body"><span id="instansi">{{ detail.jenis }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col" v-if="detail.jenis == 'Peminjaman'"><label for="">Durasi</label>
                                        <div class="card text-white" style="background-color: #AA7762;">
                                            <div class=" card-body"><span id="instansi">{{ detail.durasi }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                                </div>
                            </div>
                            <data-table :headers="headers" :items="items" :search="search" />
                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-success" @click="$emit('setuju')">
                                        <i class="fas fa-check"></i>
                                        Setuju</button>
                                    <button class="btn btn-danger" @click="$emit('tolak')">
                                        <i class="fas fa-times"></i>
                                        Tolak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>