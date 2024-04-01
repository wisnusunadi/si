<script>
import axios from 'axios';
import seriviatext from '../../../gbj/page/penerimaanRework/transfer/modalTransfer/seriviatext.vue';
export default {
    props: ['retur'],
    components: {
        seriviatext
    },
    data() {
        return {
            items: [],
            showModal: false,
            itemSelected: {},
            no_retur: '',
            loadingPaket: false,
            errorMessage: '',
        }
    },
    methods: {
        async getPaket() {
            try {
                this.loadingPaket = true
                const { data } = await axios.get(`/api/penjualan/retur_po/detail_paket/${this.retur.pesanan_id}`)
                this.items = data.map((item, idx) => {
                    return {
                        ...item,
                        no: idx + 1,
                        expanded: false,
                        qty: item.jumlah_kirim,
                        jml_retur: 0,
                        noSeriSelected: [],
                    }
                })
            } catch (error) {
                console.error(error)
            } finally {
                this.loadingPaket = false
            }
        },
        closeModal() {
            $('.modalRetur').modal('hide');
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        async toggleItem(idx) {
            // if jml_retur not 0 or null or undefined, then expanded = true
            if (this.items[idx].jml_retur !== 0 && this.items[idx].jml_retur !== null && this.items[idx].jml_retur !== '') {
                try {
                    this.items[idx].loadingProduk = true
                    const { data } = await axios.get(`/api/penjualan/retur_po/detail_prd/${this.items[idx].id}`)
                    this.items = this.items.map((item, i) => {
                        if (i === idx) {
                            return {
                                ...item,
                                noSeriSelected: [],
                                loadingProduk: false,
                                produk: data.map((prd, idx) => {
                                    return {
                                        ...prd,
                                        no: idx + 1,
                                        nama: prd.nama,
                                        max: item.jml_retur,
                                        noSeriSelected: [],
                                        noseri: prd.seri.map((n, idx) => {
                                            return {
                                                ...n,
                                            }
                                        }),
                                    }
                                }),
                                expanded: true,
                                jumlah_max_parents: data.length * item.jml_retur,
                            }
                        }
                        return item
                    })
                } catch (error) {
                    console.error(error)
                }
            } else {
                this.items[idx].expanded = false
            }
        },
        groupedNoSeri(noseri) {
            // if more than 5, then group by 5, else return noseri
            if (noseri?.length > 5) {
                return noseri.reduce((acc, curr, idx) => {
                    const group = Math.floor(idx / 5)
                    if (!acc[group]) {
                        acc[group] = []
                    }
                    acc[group].push(curr)
                    return acc
                }, [])
            } else {
                return [noseri]
            }
        },
        noseriterpakai(noseri, idxItem) {
            let found = false
            for (let i = 0; i < this.items?.length; i++) {
                if (this.items[i]?.noSeriSelected?.find((n) => n.id === noseri.id)) {
                    if (i !== idxItem) {
                        found = true
                        break
                    }
                }
            }
            return found
        },
        toggleNoSeri(item, idxProduk, noseri) {
            // mapping data and cek if noseri already selected, then remove, else add
            this.items = this.items.map((i) => {
                if (i.no === item.no) {
                    return {
                        ...i,
                        noSeriSelected: i.noSeriSelected.find((n) => n.id === noseri.id) ?
                            i.noSeriSelected.filter((n) => n.id !== noseri.id) :
                            [...i.noSeriSelected, noseri],
                        produk: i.produk.map((p) => {
                            if (p.no === item.produk[idxProduk].no) {
                                return {
                                    ...p,
                                    noSeriSelected: p.noSeriSelected.find((n) => n.id === noseri.id) ?
                                        p.noSeriSelected.filter((n) => n.id !== noseri.id) :
                                        [...p.noSeriSelected, noseri]
                                }
                            }
                            return p
                        })
                    }
                }
                return i
            })

            // check if noSeriSelected more than max, then remove last and show alert
            this.items = this.items.map((i) => {
                if (i.no === item.no) {
                    return {
                        ...i,
                        noSeriSelected: i.noSeriSelected.length > i.jumlah_max_parents ?
                            i.noSeriSelected.slice(0, -1) :
                            i.noSeriSelected,
                        produk: i.produk.map((p) => {
                            if (p.no === item.produk[idxProduk].no) {
                                if (p.noSeriSelected.length > p.max) {
                                    this.$swal('Error', 'Nomor Seri melebihi jumlah retur', 'error')
                                    this.$refs[`noseri-${noseri.id}`][0].checked = false
                                    return {
                                        ...p,
                                        noSeriSelected: p.noSeriSelected.slice(0, -1)
                                    }
                                }
                            }
                            return p
                        })
                    }
                }
                return i
            })

        },
        simpan() {
            let paket = []
            let error = false

            // push paket when on paket produk has noSeriSelected more than 0
            this.items.forEach((item) => {
                if (item.jml_retur > 0 && item.jml_retur !== null && item.jml_retur !== '') {
                    // cek produk
                    item.produk.forEach((produk) => {
                        if (produk.noSeriSelected.length > 0) {
                            paket.push(item)
                            error = false
                        } else {
                            error = true
                        }
                    })
                }
            })

            paket.forEach((item) => {
                item.produk.forEach((produk) => {
                    if (produk.noSeriSelected.length !== produk.max) {
                        error = true
                    }
                })
            })

            // remove array yang sama dari paket berdasarkan no

            paket = [...new Map(paket.map(item => [item['no'], item])).values()]

            if (this.no_retur === '') {
                this.$swal('Error', 'No Retur tidak boleh kosong', 'error')
                return
            }

            if (this.errorMessage !== '') {
                this.$swal('Error', 'No Retur sudah digunakan', 'error')
                return
            }

            if (paket.length > 0 && !error) {
                this.$swal('Success', 'Berhasil menyimpan', 'success')
            } else {
                this.$swal('Error', 'Silahkan cek kembali data yang anda masukkan', 'error')
                return
            }

            const form = {
                pesanan_id: this.retur.pesanan_id,
                no_retur: this.no_retur,
                item: paket
            }

            // ya atau tidak
            this.$swal({
                title: "Konfirmasi",
                text: "Apakah anda yakin ingin menyimpan data ini?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.value) {
                    axios.post('/api/penjualan/retur_po/kirim', form)
                        .then((res) => {
                            this.$swal('Success', 'Berhasil menyimpan', 'success')
                            this.closeModal()
                            this.$emit('refresh')
                        })
                        .catch((err) => {
                            console.error(err)
                            this.$swal('Error', 'Gagal menyimpan', 'error')
                        })
                }
            })
        },
        showModalNoSeri(idx) {
            this.showModal = true
            this.itemSelected = idx
            this.$nextTick(() => {
                $('.modalRetur').modal('hide')
                $('.modalChecked').modal('show')
            })
        },
        closeModalNoSeri() {
            this.showModal = false
            this.$nextTick(() => {
                $('.modalChecked').modal('hide')
                $('.modalRetur').modal('show')
            })
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)
            noseriarray = noseriarray.filter((n) => n !== '')
            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.items[this.itemSelected].produk.length; j++) {
                    if (this.items[this.itemSelected].produk[j].noseri.find((n) => n.noseri === noseriarray[i])) {
                        this.toggleNoSeri(this.items[this.itemSelected], j, this.items[this.itemSelected].produk[j].noseri.find((n) => n.noseri === noseriarray[i]))
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (noserinotfound.length > 0) {
                this.$swal('Error', `Nomor Seri ${noserinotfound.join(', ')} tidak ditemukan`, 'error')
            }
        },
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
        cekErrorRetur() {
            axios.post('/api/penjualan/retur_po/cek_noretur', {
                no_retur: this.no_retur
            })
                .then((res) => {
                    this.errorMessage = ''
                })
                .catch((err) => {
                    this.errorMessage = err.response.data.message
                })
        },
    },
    created() {
        this.getPaket()
    },
    watch: {
        // check jml_retur not more than qty
        'items': {
            handler: function (val) {
                val.forEach((item, idx) => {
                    if (item.jml_retur > item.qty) {
                        item.jml_retur = item.qty
                    } else if (item.jml_retur < 0) {
                        item.jml_retur = 0
                    }
                })
            },
            deep: true
        },
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showModal" @close="closeModalNoSeri" @submit="submit"></seriviatext>
        <div class="modal fade modalRetur" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Retur PO</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card removeshadow">
                            <div class="card-body border-0">
                                <h5 class="card-title pl-2 py-2">
                                    <b>{{ retur?.nama_customer }}</b>
                                </h5>
                                <ul class="fa-ul card-text">
                                    <li class="py-2">
                                        <span class="fa-li">
                                            <i class="fas fa-address-card fa-fw"></i>
                                        </span>
                                        {{ retur?.alamat }}
                                    </li>
                                    <li class="py-2">
                                        <span class="fa-li">
                                            <div class="fas fa-map-marker-alt fa-fw"></div>
                                        </span>
                                        <em class="text-muted" v-if="!retur?.provinsi">Belum Tersedia</em>
                                        {{ retur?.provinsi?.nama }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card card-outline card-tabs">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#" class="nav-link active" id="informasiretur-tab" data-toggle="tab"
                                            data-target="#informasiretur" role="tab" aria-controls="informasiretur"
                                            aria-selected="true">Informasi</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#" class="nav-link" id="produkretur-tab" data-toggle="tab"
                                            data-target="#produkretur" role="tab" aria-controls="produkretur"
                                            aria-selected="false">Produk</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="informasiretur" role="tabpanel"
                                        aria-labelledby="informasiretur-tab">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div>
                                                        <small class="text-muted">No SO</small>
                                                    </div>
                                                    <div>
                                                        <b> {{ retur?.so }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <persentase :persentase="retur.persentase"
                                                        v-if="!cekIsString(retur.persentase)" />
                                                    <span class="red-text badge" v-else>{{ retur.persentase }}</span>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b>
                                                            {{ retur?.no_po }}
                                                        </b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b>
                                                            {{ dateFormat(retur?.pesanan?.tgl_po) }}
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No DO</small></div>
                                                    <div><b v-if="retur?.no_do">
                                                            {{ retur?.no_do }}
                                                        </b>
                                                        <em v-else>Belum ada</em>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal DO</small></div>
                                                    <div><b v-if="retur?.pesanan?.tgl_do">
                                                            {{ dateFormat(retur?.pesanan?.tgl_do) }}
                                                        </b>
                                                        <em v-else>Belum ada</em>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="produkretur" role="tabpanel"
                                        aria-labelledby="produkretur-tab">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-row-reverse bd-highlight">
                                                            <div class="p-2 bd-highlight">
                                                                <div class="form-group">
                                                                    <label for="">No. Retur</label>
                                                                    <input type="text" class="form-control"
                                                                        @input="cekErrorRetur"
                                                                        :class="{ 'is-invalid': errorMessage !== '' }"
                                                                        v-model="no_retur">
                                                                    <div class="invalid-feedback">
                                                                        {{ errorMessage }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table" v-if="!loadingPaket">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Produk</th>
                                                                    <th>Qty</th>
                                                                    <th>Jumlah Retur</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template v-for="(item, idx) in items">
                                                                    <tr>
                                                                        <td>{{ item.no }}</td>
                                                                        <td>{{ item.nama }}</td>
                                                                        <td>{{ item.qty }}
                                                                        </td>
                                                                        <td><input type="number" class="form-control"
                                                                                @input="toggleItem(idx)"
                                                                                v-model.number="item.jml_retur"
                                                                                @keypress="numberOnly">

                                                                        </td>
                                                                    </tr>
                                                                    <tr v-if="item?.loadingProduk">
                                                                        <td colspan="100%">
                                                                            <div class="text-center">
                                                                                <div class="spinner-border spinner-border-sm"
                                                                                    role="status">
                                                                                    <span
                                                                                        class="sr-only">Loading...</span>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr
                                                                        v-if="item.produk !== undefined && item.expanded">
                                                                        <td colspan="100%">
                                                                            <div
                                                                                class="d-flex flex-row-reverse bd-highlight">
                                                                                <div class="p-2 bd-highlight">
                                                                                    <button class="btn btn-primary"
                                                                                        @click="showModalNoSeri(idx)">Pilih
                                                                                        Nomor Seri Via Text</button>
                                                                                </div>
                                                                            </div>
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Nama Produk</th>
                                                                                        <th>Nomor Seri
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr
                                                                                        v-for="(produk, idx2) in item.produk">
                                                                                        <td>{{ produk.no }}</td>
                                                                                        <td>{{ produk.nama }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="row">
                                                                                                <div class="col"
                                                                                                    v-for="(group, idx3) in groupedNoSeri(produk.noseri)">
                                                                                                    <div
                                                                                                        v-for="(noseri, idx4) in group">
                                                                                                        <div
                                                                                                            v-if="!noseriterpakai(noseri, idx)">
                                                                                                            <input
                                                                                                                @click="toggleNoSeri(item, idx2, noseri)"
                                                                                                                :checked="produk.noSeriSelected && produk.noSeriSelected.find((n) => n.id === noseri.id)"
                                                                                                                :ref="`noseri-${noseri.id}`"
                                                                                                                type="checkbox">
                                                                                                            {{
            noseri.noseri
        }}
                                                                                                        </div>
                                                                                                        <div v-else>
                                                                                                            <span
                                                                                                                class="badge badge-info">No
                                                                                                                Seri
                                                                                                                Terpakai</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                        <div v-else>
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border" role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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