<script>
export default {
    props: ['detail'],
    data() {
        return {
            transferProduk: [],
        }
    },
    methods: {
        closeModal() {
            $('#modelId').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        tambahTransfer() {
            this.transferProduk.push({
                jumlah: 0,
                kedatangan: 0,
            });
        },
        cekJumlah(index) {
            // tambahkan isError jika jumlah lebih dari detail.jumlah
            if (parseInt(this.transferProduk[index].jumlah) > parseInt(this.detail.jumlah)) {
                this.transferProduk[index].isError = true;
            } else {
                delete this.transferProduk[index].isError;
            }
        },
        simpan() {
            // cek semua jumlah, jika salah satu lebih dari detail.jumlah maka return
            const cek = this.transferProduk.some((item) => {
                return parseInt(item.jumlah) > parseInt(this.detail.jumlah) || parseInt(item.jumlah) == 0 || item.jumlah == ''
                    || item.jumlah == null || item.jumlah == undefined || item.jumlah == NaN || item.jumlah == 'NaN' || item.jumlah == '0'
                    || item.kedaangan == '' || item.kedatangan == null || item.kedatangan == undefined || item.kedatangan == NaN || item.kedatangan == 'NaN';
            });

            const jumlah = this.transferProduk.reduce((acc, item) => {
                return acc + parseInt(item.jumlah);
            }, 0);

            if (cek || jumlah > this.detail.jumlah) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan cek kembali inputan anda',
                });
            } else {
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang sudah di transfer tidak bisa diubah lagi",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$emit('simpan', this.transferProduk);
                        this.closeModal();
                        swal.fire(
                            'Berhasil!',
                            'Data berhasil disimpan',
                            'success'
                        );
                    }
                });
            }
        }
    },
    computed: {
        // penambahan jumlah dengan batasan detail.jumlah
        alertJumlah() {
            let jumlah = 0;
            this.transferProduk.forEach((item) => {
                jumlah += parseInt(item.jumlah);
            });
            if (jumlah > this.detail.jumlah) {
                return true;
            } else {
                return false;
            }
        },
    },
    watch: {
        // jika mengisi kedatangan, jika lebih dari 26 maka buat sama dengan 26
        transferProduk: {
            handler() {
                this.transferProduk.forEach((item) => {
                    if (item.kedatangan > 26) {
                        item.kedatangan = 26;
                    } else if (item.kedatangan < 0) {
                        item.kedatangan = 1;
                    } else if (item.kedatangan == '') {
                        item.kedatangan = 0;
                    }
                });
            },
            deep: true,
        },
        alertJumlah: {
            handler() {
                if (this.alertJumlah) {
                    this.transferProduk.forEach((item) => {
                        item.isError = true;
                    });
                } else {
                    this.transferProduk.forEach((item) => {
                        delete item.isError;
                    });
                }
            },
            immediate: true,
        }
    }
}
</script>
<template>
    <div class="modal fade" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Transfer Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <small>
                        <span class="text-danger">*</span>
                        Jumlah yang belum di transfer: {{ detail.jumlah }}
                    </small>
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <button class="btn btn-primary" @click="tambahTransfer">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jumlah</th>
                                <th colspan="2">Kedatangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in transferProduk" :key="index">
                                <td>
                                    <input type="number" class="form-control" v-model="item.jumlah"
                                        :class="item?.isError ? 'is-invalid' : ''"
                                        @input="cekJumlah(index); numberOnly($event)">
                                    <div class="invalid-feedback">
                                        Jumlah melebihi yang belum di transfer
                                    </div>

                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="item.kedatangan"
                                        @keyup="numberOnly($event)">
                                </td>
                                <td>
                                    <button class="btn btn-outline-danger" @click="transferProduk.splice(index, 1)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="100%">
                                    <small :class="alertJumlah ? 'text-danger' : ''">
                                        Jumlah Total : {{ transferProduk.reduce((acc, item) => acc +
                        parseInt(item.jumlah), 0) }}
                                    </small> <br>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>