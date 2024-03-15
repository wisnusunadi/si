<script>
export default {
    props: ['item'],
    data() {
        return {
            permintaan: [],
        }
    },
    methods: {
        addPermintaan() {
            this.permintaan.push({
                jumlah: 0,
                kedatangan: 0,
            });
        },
        deletePermintaan(index) {
            this.permintaan.splice(index, 1);
        },
    },
    watch: {
        permintaan: {
            handler: function (val) {
                // input permintaan ke parent item.permintaan
                val.forEach((item, index) => {
                    if (item.kedatangan > 26) {
                        this.permintaan[index].kedatangan = 26;
                    } else if (item.kedatangan < 0) {
                        this.permintaan[index].kedatangan = 0;
                    }

                    if (item.jumlah > this.item.jumlah) {
                        this.permintaan[index].jumlah = this.item.jumlah;
                    } else if (item.jumlah < 0) {
                        this.permintaan[index].jumlah = 0;
                    }
                });
                const produk = {
                    ...this.item,
                    permintaan: val,
                }
                this.$emit('inputProduk', produk);
            },
            deep: true,
        },
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                    <div class="margin"><small class="text-muted">Nama Produk</small></div>
                    <div class="margin"><b>{{ item.nama }}</b></div>
                </div>
                <div class="col">
                    <div class="p-2">
                        <div class="margin"><small class="text-muted">
                                Jumlah Belum Ditransfer
                            </small></div>
                        <div class="margin"><b>{{ item.jumlah }}</b></div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-2 text-right">
                        <button class="btn btn-primary" @click="addPermintaan">Tambah</button>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Jumlah</th>
                        <th colspan="2">Kedatangan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody v-for="(minta, key) in permintaan" :key="key">
                    <tr>
                        <td><input type="number" class="form-control" v-model="minta.jumlah"
                                @keypress="numberOnly($event)">
                        </td>
                        <td><input type="number" class="form-control" v-model="minta.kedatangan"
                                @keypress="numberOnly($event)"></td>
                        <td> <button class="btn btn-outline-danger" @click="deletePermintaan(key)">
                                <i class="fa fa-trash"></i>
                            </button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>