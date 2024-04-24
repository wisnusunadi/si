<script>
import noseri from './noseri.vue';
export default {
    components: {
        noseri
    },
    data() {
        return {
            form: {
                tanggal: new Date().toISOString().substr(0, 10),
                tanggal_selesai: null,
            },
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah Dipinjam',
                    value: 'jumlah'
                },
                {
                    text: 'Jumlah No. Seri Dipilih',
                    value: 'noseri'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            items: [
                {
                    id: 1,
                    no: 1,
                    nama: 'Produk 3',
                    jumlah: 3,
                },
                {
                    id: 2,
                    no: 2,
                    nama: 'Produk 1',
                    jumlah: 2,
                }
            ],
            search: '',
            showModal: false,
            detailSelected: null,
            loading: false
        }
    },
    methods: {
        closeModal() {
            $('.modalPerubahan').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        openModalNoSeri(item) {
            this.detailSelected = item;
            this.showModal = true;
            $('.modalPerubahan').modal('hide');
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show');
            });
        },
        closeModalNoSeri() {
            this.showModal = false;
            $('.modalNoSeri').modal('hide');
            this.$nextTick(() => {
                $('.modalPerubahan').modal('show');
            });
        },
        setProduk(produk) {
            this.loading = true;
            const index = this.items.findIndex(x => x.id === produk.id);
            this.items[index] = JSON.parse(JSON.stringify(produk));
            this.$nextTick(() => {
                this.loading = false;
            });
        },
        simpan() {
            console.log(this.items);
            swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
            this.closeModal();
        }
    },
}
</script>
<template>
    <div>
        <noseri v-if="showModal" @close="closeModalNoSeri" :detail="detailSelected" @submit="setProduk" />
        <div class="modal fade modalPerubahan" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Permintaan Perubahan Durasi Peminjaman</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-6 text-right">Tanggal Akhir Peminjaman Sebelum Perubahan</label>
                                    <input type="date" readonly class="form-control col-3" v-model="form.tanggal">
                                </div>
                                <div class="form-group row">
                                    <label class="col-6 text-right">Tanggal Akhir Peminjaman Setelah Perubahan</label>
                                    <input type="date" class="form-control col-3" :min="form.tanggal"
                                        v-model="form.tanggal_selesai">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                                    </div>
                                </div>
                                <data-table :headers="headers" :items="items" :search="search" v-if="!loading">
                                    <template #item.noseri="{ item }">
                                        <div>
                                            {{ item.noseri?.length ?? 0 }}
                                        </div>
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <div>
                                            <button class="btn btn-primary btn-sm" @click="openModalNoSeri(item)">
                                                <i class="fas fa-qrcode"></i>
                                                Nomor Seri
                                            </button>
                                        </div>
                                    </template>
                                </data-table>
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
    </div>
</template>