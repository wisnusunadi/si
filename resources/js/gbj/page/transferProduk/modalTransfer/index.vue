<script>
import axios from 'axios'
import noseri from './noseri.vue'
export default {
    props: ['data'],
    components: {
        noseri
    },
    data() {
        return {
            search: '',
            produk: [],
            showModal: false,
            detailSelected: null,
            paketSelected: null
        }
    },
    methods: {
        closeModal() {
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        showModalNoseri(detail, paket) {
            this.detailSelected = detail
            this.paketSelected = paket
            this.showModal = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show')
                $('.modalTransfer').modal('hide')
            })
        },
        closeModalNoseri() {
            this.showModal = false
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
        async transfer() {
            // cek jika salah satu produk belum memiliki no seri
            let produkNoSeri = []

            this.produk.forEach(paket => {
                paket.item.forEach(item => {
                    if (item.noseri) {
                        produkNoSeri.push(item)
                    }
                })
            })

            console.log(produkNoSeri)

            if (produkNoSeri.length == 0) {
                swal.fire('Error', 'Produk belum memiliki no seri', 'error');
            } else {
                try {
                    const produk = {
                        pesanan_id: this.data.id,
                        produk: produkNoSeri
                    }
                    const { data } = await axios.post(`/api/tfp/byso-final`, produk, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                        }
                    })
                    swal.fire('Success', 'Produk berhasil di transfer', 'success');
                    this.$emit('refresh')
                    this.closeModal()
                } catch (error) {
                    swal.fire('Error', 'Terjadi kesalahan', 'error');
                }
            }
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/tfp/detail-transfer-so/${this.data.id}`)
                this.produk = data.map(paket => {
                    return {
                        ...paket,
                        persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                        persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                        item: paket.item.map(item => {
                            return {
                                ...item,
                                persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                                persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error)
            }
        },
        persentase(jmlPerItem, jmlTotal) {
            let item = parseInt(jmlPerItem)
            let total = parseInt(jmlTotal)
            return Math.round((item / total) * 100)
        },
        async batalkan() {
            try {
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan bisa mengembalikan data yang sudah dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, batalkan!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const { data } = await axios.post(`/api/tfp/byso-batal/${this.data.id}`)
                        swal.fire('Success', 'Persiapan berhasil dibatalkan', 'success');
                        this.$emit('refresh')
                        this.closeModal()
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    created() {
        this.getData()
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
    }
}
</script>
<template>
    <div>
        <noseri v-if="showModal" :detailSelected="detailSelected" @close="closeModalNoseri" :paket="paketSelected"
            :allPaket="produk" @submit="noseriSelected" />
        <div class="modal fade modalTransfer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nomor SO</label>
                                        <div class="card text-white" style="background-color: #717FE1;">
                                            <div class="card-body">
                                                <span id="so">{{ data.so }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor AKN</label>
                                        <div class="card text-white" style="background-color: #DF7458;">
                                            <div class="card-body">
                                                <span id="akn">{{ data.ekatalog?.no_paket ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nomor PO</label>
                                        <div class="card text-white" style="background-color: #85D296;">
                                            <div class="card-body">
                                                <span id="po">{{ data.no_po }}</span>
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
                                            <th>Jumlah No Seri Dipilih</th>
                                            <th>Merk</th>
                                            <th>Progress</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="filterRecursive.length > 0">
                                        <template v-for="paket in filterRecursive">
                                            <tr class="table-dark">
                                                <td colspan="100%">
                                                    {{ paket.nama }} <br>
                                                    <span class="badge badge-light">Belum Transfer: {{ paket.jumlah_sisa }}
                                                        ({{ paket.
                                                            persentase_belum }}%)</span>
                                                    <span class="badge badge-warning">
                                                        Sudah Transfer: {{ paket.jumlah_gudang }} ({{
                                                            paket.persentase_gudang
                                                        }}%)
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-for="item in paket.item" :key="item.id">
                                                <td>{{ item.nama }}</td>
                                                <td>{{ item.jumlah_sisa }}</td>
                                                <td>{{ item.noseri?.length ?? 0 }}</td>
                                                <td>{{ item.merk }}</td>
                                                <td>
                                                    <span class="badge badge-info">Belum Transfer: {{ item.jumlah_sisa }}
                                                        ({{
                                                            item.persentase_belum }}%)</span> <br>
                                                    <span class="badge badge-warning">Sudah Transfer: {{ item.jumlah_gudang
                                                    }}
                                                        ({{
                                                            item.persentase_sudah
                                                        }}%)</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" @click="showModalNoseri(item, paket)"
                                                        v-if="item.status">
                                                        <i class="fa fa-qrcode"></i>
                                                        Scan Barcode
                                                    </button>
                                                    <span v-else>
                                                        {{ item.persentase_sudah == 100 ? 'Produk Sudah Ditransfer' : 'Siapkan Produk Dahulu'}}
                                                    </span>
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
                        <button class="btn btn-success" @click="transfer">Transfer</button>
                        <button class="btn btn-info" v-if="data.jumlah_gdg == 0" @click="batalkan">Batalkan
                            Persiapan</button>
                        <button class="btn btn-secondary" @click="closeModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>