<script>
import axios from 'axios';
import noseri from './noseri.vue';
export default {
    props: ['detail'],
    components: {
        noseri
    },
    data() {
        return {
            produk: [],
            search: '',
            detailSelected: null,
            paketSelected: null,
            showModal: false
        }
    },
    methods: {
        async getData() {
            try {
                const { data } = await axios.get(`/api/gbj/batal_po/detail/${this.detail.id}`)
                this.produk = data
            } catch (error) {
                console.error(error)
            }
        },
        closeModal() {
            $('.modalTransfer').modal('hide');
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        showModalNoseri(detail, paket) {
            this.detailSelected = detail;
            this.paketSelected = paket;
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
                $('.modalTransfer').modal('hide')
            })
        },
        closeModalNoSeri() {
            this.showModal = false;
            this.$nextTick(() => {
                $('.modalNoSeri').modal('hide')
                $('.modalTransfer').modal('show')
            })
        },
        noseriSelected(paket) {
            this.produk = this.produk.map(p => {
                if (p.id === paket.id) {
                    p = paket
                }
                return p
            })
        },
        transfer() {
            let produkNoSeri = []

            this.produk.forEach(paket => {
                paket.produk.forEach(item => {
                    if (item.noseri) {
                        produkNoSeri.push(item)
                    }
                })
            })

            if (produkNoSeri.length == 0) {
                swal.fire('Error', 'Produk belum memiliki no seri', 'error');
                return
            }

            swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah di transfer tidak bisa diubah lagi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/gbj/batal_po/kirim', {
                        item: produkNoSeri
                    }).then(() => {
                        swal.fire('Success', 'Produk berhasil dikirim', 'success');
                        this.closeModal()
                        this.$emit('refresh')
                    }).catch(err => {
                        swal.fire('Error', err.response.data.message, 'error');
                    })
                }
            })


        },
        progressTransfer(item) {
            if (item.jumlah_tf == 0) {
                return {
                    text: 'Belum Transfer',
                    color: 'badge-danger'
                }
            } else if (item.jumlah == item.jumlah_tf) {
                return {
                    text: 'Sudah Transfer',
                    color: 'badge-success'
                }
            } else {
                return {
                    text: 'Sudah Transfer Sebagian',
                    color: 'badge-warning'
                }
            }
        },
    },
    computed: {
        filterRecursive() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.produk.filter(paket => {
                return includesSearch(paket, this.search);
            });

        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <noseri v-if="showModal" :detailSelected="detailSelected" :allPaket="produk" :paket="paketSelected"
            @close="closeModalNoSeri" @submit="noseriSelected"></noseri>
        <div class="modal fade modalTransfer" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Batal Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm"><label for="">Nomor SO</label>
                                        <div class="card text-white" style="background-color: rgb(113, 127, 225);">
                                            <div class="card-body"><span id="so">{{ detail.so ?? '-' }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm"><label for="">Nomor PO</label>
                                        <div class="card text-white" style="background-color: rgb(223, 116, 88);">
                                            <div class="card-body"><span id="akn">{{ detail.no_po ?? '-' }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm"><label for="">Customer</label>
                                        <div class="card text-white" style="background-color: rgb(133, 210, 150);">
                                            <div class="card-body"><span id="po">{{ detail.customer ?? '-' }}</span>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th v-if="detail.jumlah_tf != detail.jumlah">Jumlah No Seri Dipilih
                                            </th>
                                            <th>Merk</th>
                                            <th>Progress</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="filterRecursive.length > 0">
                                        <template v-for="paket in filterRecursive">
                                            <tr class="table-dark">
                                                <td colspan="100%">
                                                    {{ paket.nama }}
                                                </td>
                                            </tr>
                                            <tr v-for="item in paket.produk" :key="item.id">
                                                <td>{{ item.nama }}</td>
                                                <td>{{ item.jumlah }}</td>
                                                <td v-if="detail.jumlah_tf != detail.jumlah">{{ item.noseri?.length
            ?? 0 }}</td>
                                                <td>{{ item.merk }}</td>
                                                <td>
                                                    <span :class="'badge ' + progressTransfer(item).color">{{
            progressTransfer(item).text }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary"
                                                        @click="showModalNoseri(item, paket)">
                                                        <i class="fa fa-qrcode"></i>
                                                        Nomor Seri
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="100%" class="text-center">Tidak ada data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-success" @click="transfer"
                            v-if="detail.jumlah_tf != detail.jumlah">Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>