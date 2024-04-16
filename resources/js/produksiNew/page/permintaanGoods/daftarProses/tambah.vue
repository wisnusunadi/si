<script>
export default {
    data() {
        return {
            form: {
                jenis: null,
                tgl_permintaan: new Date().toISOString().substr(0, 10),
                tgl_kebutuhan: null,
                tujuan: null,
            },
            jenisChoices: [
                { label: 'Peminjaman', value: 'peminjaman' },
                { label: 'Permintaan', value: 'permintaan' },
            ],
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Stok',
                    value: 'stok'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                }
            ],
            items: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalTambah').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        tambahProduk() {
            this.items.push({
                nama_produk: null,
                stok: null,
                jumlah: null
            });
        }
    },
    watch: {
        'form.jenis': function (val) {
            if (val === 'peminjaman') {
                this.form.tgl_pengembalian = null;
            } else {
                delete this.form.tgl_pengembalian;
            }
        }
    }
}
</script>
<template>
    <div class="modal fade modalTambah" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Permintaan Barang</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Jenis</label>
                                        <v-select :options="jenisChoices" v-model="form.jenis"></v-select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Kebutuhan</label>
                                        <input type="date" class="form-control" v-model="form.tgl_kebutuhan">
                                    </div>
                                    <div class="form-group" v-if="form?.jenis?.value == 'peminjaman'">
                                        <label for="">Tanggal Pengembalian</label>
                                        <input type="date" class="form-control" v-model="form.tgl_pengembalian">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tujuan Permintaan</label>
                                        <textarea class="form-control" cols="5" v-model="form.tujuan"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary" @click="tambahProduk">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                            <data-table :headers="headers" :items="items">
                                <template #item.no="{item, index}">
                                    <div>
                                        {{ idx + 1 }}
                                    </div>
                                </template>
                            </data-table>
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
</template>