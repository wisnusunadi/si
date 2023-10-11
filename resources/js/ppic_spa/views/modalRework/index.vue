<script>
import axios from 'axios';
import detailProduk from './detailproduk.vue';
import moment from 'moment';
export default {
    props: ['showModal', 'dataProduk'],
    components: {
        detailProduk
    },
    data() {
        return {
            judul: this.dataProduk?.id ? 'Form Edit Penjadwalan Rework' : 'Form Tambah Penjadwalan Rework',
            produk: [],
            showDetailProduk: false,
            showModalNow: false,
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal')
        },
        async getProduk() {
            const { data } = await axios.get('/api/ppic/data/gbj')
            this.produk = data.map((item) => {
                return {
                    id: item.id,
                    label: `${item.produk.nama} ${item.nama}`
                }
            })
            this.showModalNow = true
        },
        detailProdukButton() {
            if (!this.dataProduk.detail) {
                this.dataProduk.detail = [
                    {
                        produk: null,
                        jumlah: null
                    },
                    {
                        produk: null,
                        jumlah: null
                    }
                ]
            }
            this.showModalNow = false
            this.$nextTick(() => {
                this.showDetailProduk = true
            })
        },
        simpan() {
            this.$emit('simpan')
        },
    },
    mounted() {
        this.getProduk()
    },
    computed: {
        tanggalMulaiError() {
            const tanggal_mulai = moment(this.dataProduk.tanggal_mulai)
            const tanggal_selesai = moment(this.dataProduk.tanggal_selesai)
            if (tanggal_mulai.isAfter(tanggal_selesai)) {
                return true
            }
            return false
        },
        tanggalSelesaiError() {
            const tanggal_mulai = moment(this.dataProduk.tanggal_mulai)
            const tanggal_selesai = moment(this.dataProduk.tanggal_selesai)
            if (tanggal_selesai.isBefore(tanggal_mulai)) {
                return true
            }
            return false
        }
    }
}
</script>
<template>
    <div>
        <detailProduk v-if="showDetailProduk" :dataProduk="dataProduk" :showModal="showDetailProduk"
            @closeModal="showDetailProduk = false, showModalNow = true" />
        <div class="modal" :class="{ 'is-active': showModalNow }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ judul }}</p>
                    <button class="delete" @click="closeModal"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Tanggal Mulai</label>
                                <div class="control">
                                    <input class="input" type="date" placeholder="Text input"
                                        :class="{ 'is-danger': tanggalMulaiError }" v-model="dataProduk.tanggal_mulai">
                                    <p class="help is-danger" v-if="tanggalMulaiError">
                                        Tanggal mulai harus lebih kecil dari tanggal selesai
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Tanggal Selesai</label>
                                <div class="control">
                                    <input class="input" type="date" placeholder="Text input"
                                        :class="{ 'is-danger': tanggalSelesaiError }"
                                        v-model="dataProduk.tanggal_selesai">
                                    <p class="help is-danger" v-if="tanggalSelesaiError">
                                        Tanggal selesai harus lebih besar dari tanggal mulai
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Nama Rework</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <v-select :options="produk" label="label" v-model="dataProduk.nama_produk" />
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Jumlah</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <input class="input" type="text" placeholder="Jumlah" v-model="dataProduk.jumlah"
                                        @keypress="numberOnly($event)">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Stok GBJ</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <input class="input" type="text" placeholder="Stok GBJ" v-model="dataProduk.stok_gbj"
                                        @keypress="numberOnly($event)" disabled>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label"></label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <button class="button is-link is-light" @click="detailProdukButton">
                                        Detail Produk
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div> -->
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" @click="simpan">Tambah</button>
                </footer>
            </div>
        </div>
    </div>
</template>
