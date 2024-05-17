<script>
export default {
    data() {
        return {
            form: {
                "jenis": {
                    "label": "Peminjaman",
                    "value": "peminjaman"
                },
                "tgl_permintaan": "2024-04-16",
                "tgl_kebutuhan": "2024-04-16",
                "tujuan": "okeee",
                "tgl_pengembalian": "2024-04-16"
            },
            jenisChoices: [
                { label: 'Peminjaman', value: 'peminjaman' },
                { label: 'Permintaan', value: 'permintaan' },
            ],
            produkChoices: [
                { label: 'Produk 1', value: 'produk_1', stok: 100 },
                { label: 'Produk 2', value: 'produk_2', stok: 200 },
                { label: 'Produk 3', value: 'produk_3', stok: 0 },
            ],
            items: [
                {
                    "nama_produk": {
                        "label": "Produk 1",
                        "value": "produk_1",
                        "stok": 100
                    },
                    "stok": 100,
                    "jumlah": "12"
                },
                {
                    "nama_produk": {
                        "label": "Produk 2",
                        "value": "produk_2",
                        "stok": 200
                    },
                    "stok": 200,
                    "jumlah": "45"
                }
            ],
            copyAllFormAndItems: {}
        }
    },
    methods: {
        closeModal() {
            $('.modalEdit').modal('hide');
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
        compareJson() {
            const changes = []

            const formBefore = this.copyAllFormAndItems.form;
            const formAfter = this.form;
            const itemsBefore = this.copyAllFormAndItems.items;
            const itemsAfter = this.items;

            // compare form fields
            for (let key in formBefore) {
                if (formBefore[key] !== formAfter[key]) {
                    if (typeof formBefore[key] === 'object' && typeof formAfter[key] === 'object') {
                        for (let subKey in formBefore[key]) {
                            if (formBefore[key][subKey] !== formAfter[key][subKey]) {
                                changes.push(`Perubahan pada form ${key} ${subKey} dari ${formBefore[key][subKey]} menjadi ${formAfter[key][subKey]}`)
                            }
                        }
                    } else {
                        changes.push(`Perubahan pada form ${key} dari ${formBefore[key]} menjadi ${formAfter[key]}`)
                    }
                }
            }

            // Compare items
            itemsBefore.forEach((itemBefore, index) => {
                const itemAfter = itemsAfter[index];
                if (itemAfter) {
                    if (itemBefore.nama_produk.label !== itemAfter.nama_produk.label) {
                        changes.push(`Perubahan produk dari ${itemBefore.nama_produk.label} menjadi ${itemAfter.nama_produk.label}`)
                    }
                    // compare jumlah
                    if (itemBefore.jumlah !== itemAfter.jumlah) {
                        changes.push(`Perubahan jumlah produk ${itemAfter.nama_produk.label} dari ${itemBefore.jumlah} menjadi ${itemAfter.jumlah}`)
                    }
                } else {
                    changes.push(`Produk ${itemBefore.nama_produk.label} dihapus`)
                }
            })

            // detect new item
            if (itemsAfter.length > itemsBefore.length) {
                const newItems = itemsAfter.slice(itemsBefore.length);
                newItems.forEach(item => {
                    changes.push(`Produk ${item.nama_produk.label} ditambahkan`)
                })
            }

            return changes;
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
                return
            }

            // cek tanggal pengembalian harus lebih besar dari tanggal kebutuhan
            if (this.form.jenis.value === 'peminjaman' && this.form.tgl_pengembalian < this.form.tgl_kebutuhan) {
                swal.fire('Peringatan !', 'Tanggal pengembalian harus lebih besar dari tanggal kebutuhan', 'warning');
                return;
            }

            console.log(this.form);
            console.log(this.items);
            swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
            this.$emit('refresh');
            this.closeModal();
            console.log(this.compareJson());
        },
        checkProdukFilled(idx) {
            const selectedProduk = this.items.map(item => item.nama_produk?.value);

            return this.produkChoices.filter((produk) => {
                return !selectedProduk.includes(produk.value) || this.items[idx].nama_produk?.value === produk.value;
            });
        }
    },
    watch: {
        'form.jenis': function (val) {
            if (val.value === 'peminjaman') {
                // add tgl_pengembalian key to form object if jenis is peminjaman, if not delete tgl_pengembalian key
                this.$set(this.form, 'tgl_pengembalian', null);
            } else {
                this.$delete(this.form, 'tgl_pengembalian');
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
                            this.$set(this.items[index], 'jumlah', null);
                        } else {
                            this.$delete(this.items[index], 'isDisabled');
                        }
                    }
                });
            },
            deep: true
        }
    },
    mounted() {
        this.copyAllFormAndItems = JSON.parse(JSON.stringify({ form: this.form, items: this.items }));
    }
}
</script>
<template>
    <div class="modal fade modalEdit" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form {{ form.jenis?.value == 'permintaan' ? 'Permintaan' : 'Peminjaman' }}
                        Edit Barang</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-5 text-right">Jenis</label>
                                <select v-model="form.jenis" class="form-control col-4" disabled>
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
                                <input type="date" :min="form.tgl_kebutuhan" class="form-control col-4"
                                    v-model="form.tgl_pengembalian">
                            </div>
                            <div class="form-group row">
                                <label class="col-5 text-right">Tujuan {{ form.jenis?.value == 'permintaan' ?
                                    'Permintaan' : 'Peminjaman' }}</label>
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
                                            <v-select :options="checkProdukFilled(index)"
                                                v-model="item.nama_produk"></v-select>
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