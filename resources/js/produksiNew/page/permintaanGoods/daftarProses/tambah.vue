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
            produkChoices: [
                { label: 'Produk 1', value: 'produk_1', stok: 100},
                { label: 'Produk 2', value: 'produk_2', stok: 200},
                { label: 'Produk 3', value: 'produk_3', stok: 0 },
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
        },
        simpan() {
            // check every form is filled
            const form = Object.entries(this.form).every(([key, value]) => {
                if (key === 'tgl_pengembalian') {
                    return this.form.jenis.value === 'peminjaman' ? value : true;
                }
                return value;
            });

            const items = this.items.every(item => item.nama_produk && item.jumlah);

            if (!form || !items || this.items.length === 0) {
                swal.fire('Peringatan', 'Pastikan semua form terisi', 'warning')
            } else {
                // do something
                console.log(this.form);
                console.log(this.items);
            }
        }
    },
    watch: {
        'form.jenis': function (val) {
            if (val.value === 'peminjaman') {
                // add tgl_pengembalian key to form object if jenis is peminjaman, if not delete tgl_pengembalian key
                this.$set(this.form, 'tgl_pengembalian', null);
            } else {
                delete this.form.tgl_pengembalian;
            }
        },
        'items': {
            handler: function (val) {
                val.forEach((item, index) => {
                    if (item.nama_produk) {
                        const produk = this.produkChoices.find(p => p.value === item.nama_produk.value);
                        this.$set(this.items[index], 'stok', produk.stok);
                        // jika stok sama dengan 0, buat disabled input jumlah dengan menambahkan object key baru isDisabled = true, jika tidak delete key tersebut
                        if (produk.stok === 0) {
                            this.$set(this.items[index], 'isDisabled', true);
                        } else {
                            delete this.items[index].isDisabled;
                        }
                    }
                });
            },
            deep: true
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
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-5 text-right">Jenis</label>
                                <select v-model="form.jenis" class="form-control col-4">
                                    <option v-for="choice in jenisChoices" :value="choice" :key="choice.value">{{
                                        choice.label }}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-5 text-right">Tanggal Kebutuhan</label>
                                <input type="date" class="form-control col-4" v-model="form.tgl_kebutuhan">
                            </div>
                            <div class="form-group row" v-if="form?.jenis?.value == 'peminjaman'">
                                <label class="col-5 text-right">Tanggal Pengembalian</label>
                                <input type="date"
                                :min="form.tgl_kebutuhan"
                                class="form-control col-4" v-model="form.tgl_pengembalian">
                            </div>
                            <div class="form-group row">
                                <label class="col-5 text-right">Tujuan Permintaan</label>
                                <textarea class="form-control col-4" cols="5" v-model="form.tujuan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-primary" @click="tambahProduk">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th colspan="2">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in items" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            <v-select :options="produkChoices" v-model="item.nama_produk"></v-select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="item.stok" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="item.jumlah"
                                                :disabled="item?.isDisabled">
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-danger btn-sm"
                                                @click="items.splice(index, 1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>