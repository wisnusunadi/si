<script>
import seriviatext from '../../../gbj/page/penerimaanRework/transfer/modalTransfer/seriviatext.vue';
export default {
    props: ['retur'],
    components: {
        seriviatext
    },
    data() {
        return {
            items: [
                {
                    no: 1,
                    nama: 'MOL-01 + UPS',
                    expanded: false,
                    qty: 1,
                    jml_retur: 0,
                    noSeriSelected: [],
                },
                {
                    no: 2,
                    nama: 'DIGIT ONE BABY',
                    expanded: false,
                    qty: 1,
                    jml_retur: 0,
                    noSeriSelected: [],
                },
            ],
            showModal: false,
            itemSelected: {},
            noretur: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalRetur').modal('hide');
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        toggleItem(idx) {
            // if jml_retur not 0 or null or undefined, then expanded = true
            if (this.items[idx].jml_retur !== 0 && this.items[idx].jml_retur !== null && this.items[idx].jml_retur !== '') {
                this.items[idx].expanded = true

                this.items[idx].produk = [
                    {
                        no: 1,
                        nama: 'MOL-01',
                        max: this.items[idx].jml_retur,
                        noSeriSelected: [],
                        noseri: [
                            {
                                id: 1,
                                noseri: '1',
                            },
                            {
                                id: 2,
                                noseri: '2',
                            },
                            {
                                id: 3,
                                noseri: '3',
                            },
                            {
                                id: 4,
                                noseri: '4',
                            },
                            {
                                id: 5,
                                noseri: '5',
                            },
                            {
                                id: 6,
                                noseri: '6',
                            },
                            {
                                id: 7,
                                noseri: '7',
                            },
                            {
                                id: 8,
                                noseri: '8',
                            },
                            {
                                id: 9,
                                noseri: '9',
                            },
                            {
                                id: 10,
                                noseri: '10',
                            },
                            {
                                id: 11,
                                noseri: '11',
                            },
                            {
                                id: 12,
                                noseri: '12',
                            },
                            {
                                id: 13,
                                noseri: '13',
                            },
                            {
                                id: 14,
                                noseri: '14',
                            },
                            {
                                id: 15,
                                noseri: '15',
                            },
                            {
                                id: 16,
                                noseri: '16',
                            },
                            {
                                id: 17,
                                noseri: '17',
                            },
                            {
                                id: 18,
                                noseri: '18',
                            },
                            {
                                id: 19,
                                noseri: '19',
                            },
                            {
                                id: 20,
                                noseri: '20',
                            },
                            {
                                id: 21,
                                noseri: '21',
                            },
                            {
                                id: 22,
                                noseri: '22',
                            },
                            {
                                id: 23,
                                noseri: '23',
                            },
                            {
                                id: 24,
                                noseri: '24',
                            },
                        ],
                    },
                    {
                        no: 2,
                        nama: 'UPS',
                        max: this.items[idx].jml_retur,
                        noSeriSelected: [],
                        noseri: [
                            {
                                id: 25,
                                noseri: '25',
                            },
                            {
                                id: 26,
                                noseri: '26',
                            },
                            {
                                id: 27,
                                noseri: '27',
                            },
                            {
                                id: 28,
                                noseri: '28',
                            },
                            {
                                id: 29,
                                noseri: '29',
                            },
                            {
                                id: 30,
                                noseri: '30',
                            },
                            {
                                id: 31,
                                noseri: '31',
                            },
                            {
                                id: 32,
                                noseri: '32',
                            },
                            {
                                id: 33,
                                noseri: '33',
                            },
                            {
                                id: 34,
                                noseri: '34',
                            },
                            {
                                id: 35,
                                noseri: '35',
                            },
                            {
                                id: 36,
                                noseri: '36',
                            },
                            {
                                id: 37,
                                noseri: '37',
                            },
                            {
                                id: 38,
                                noseri: '38',
                            },
                            {
                                id: 39,
                                noseri: '39',
                            },
                            {
                                id: 40,
                                noseri: '40',
                            },
                            {
                                id: 41,
                                noseri: '41',
                            },
                            {
                                id: 42,
                                noseri: '42',
                            },
                            {
                                id: 43,
                                noseri: '43',
                            },
                            {
                                id: 44,
                                noseri: '44',
                            },
                            {
                                id: 45,
                                noseri: '45',
                            },
                            {
                                id: 46,
                                noseri: '46',
                            },
                            {
                                id: 47,
                                noseri: '47',
                            },
                            {
                                id: 48,
                                noseri: '48',
                            },
                        ],
                    }
                ]
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
            // if noseri not in noSeriSelected, then push, else remove
            const idx = this.items.findIndex((i) => i.no === item.no)
            if (!item.noSeriSelected.find((n) => n.id === noseri.id)) {
                if (!this.noseriterpakai(noseri, idx)) {
                    item.noSeriSelected.push(noseri)
                }
            } else {
                item.noSeriSelected = item.noSeriSelected.filter((n) => n.id !== noseri.id)
            }

            // // push to noseriselected
            if (!item.produk[idxProduk].noSeriSelected.find((n) => n.id === noseri.id)) {
                if (!this.noseriterpakai(noseri, idx)) {
                    item.produk[idxProduk].noSeriSelected.push(noseri)
                }
            } else {
                item.produk[idxProduk].noSeriSelected = item.produk[idxProduk].noSeriSelected.filter((n) => n.id !== noseri.id)
            }


            if (item.produk[idxProduk].noSeriSelected.length > item.produk[idxProduk].max) {
                this.$swal('Error', 'Jumlah retur melebihi jumlah qty', 'error')
                item.produk[idxProduk].noSeriSelected.pop()
                item.noSeriSelected.pop()
                this.$refs[`noseri-${noseri.id}-${idx}-${idxProduk}`][0].checked = false
                return
            }
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

            // remove array yang sama dari paket berdasarkan no

            paket = [...new Map(paket.map(item => [item['no'], item])).values()]

            if (this.noretur === '') {
                this.$swal('Error', 'No Retur tidak boleh kosong', 'error')
                return
            }

            if (paket.length > 0 && !error) {
                this.$swal('Success', 'Berhasil menyimpan', 'success')
            } else {
                this.$swal('Error', 'Silahkan lengkapi data', 'error')
                return
            }

            const form = [
                this.noretur,
                paket
            ]

            console.log(form)
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
                                                    <div>
                                                        <small class="text-muted">Status</small>
                                                        <persentase :persentase="retur.persentase" />
                                                    </div>
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
                                                                        v-model="noretur">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table">
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
                                                                                        <th>Nomor Seri</th>
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
                                                                                                                :ref="`noseri-${noseri.id}-${idx}-${idx2}`"
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