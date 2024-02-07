<script>
import axios from 'axios';
import DataTable from '../../../components/DataTable.vue';
export default {
    components: { DataTable },
    props: ['detail'],
    data() {
        return {
            hasilPengujian: [],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalPengujian').modal('hide');
            this.hasilPengujian = [];
            this.$nextTick(() => {
                this.$emit('close');
            })

        },
        async getDetail() {
            try {
                if (this.detail.jenis == 'produk') {
                    let { data } = await axios.post(`/api/qc/so/riwayat/detail/${this.detail.detail_pesanan_produk[0].id}/produk`).then(res => res.data);
                    this.hasilPengujian = data;
                } else {
                    let { data } = await axios.post(`/api/qc/so/riwayat/detail/${this.detail.id}/part`).then(res => res.data);
                    this.hasilPengujian = data;
                }
            } catch (error) {
                console.log(error);
            }
        }
    },
    created() {
        this.getDetail();
    },
    computed: {
        headers() {
            let prd = [
                {
                    text: 'No',
                    value: 'DT_RowIndex',
                },
                {
                    text: 'No Seri',
                    value: 'no_seri',
                },
                {
                    text: 'Hasil',
                    value: 'hasil',
                }
            ]

            let part = [
                {
                    text: 'No',
                    value: 'DT_RowIndex',
                },
                {
                    text: 'Tanggal Uji',
                    value: 'tanggal_uji',
                },
                {
                    text: 'ok',
                    value: 'jumlah_ok',
                },
                {
                    text: 'notok',
                    value: 'jumlah_nok',
                }
            ]

            if (this.detail.jenis == 'produk') {
                return prd;
            } else {
                return part;
            }
        }
    }
}
</script>
<template>
    <div class="modal fade modalPengujian" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    Info Customer
                                </div>
                                <div class="card-body text-center">
                                    <b>{{ detail.customer.nama }}</b>
                                    <p v-if="detail.customer.jenis == 'ekatalog'">{{ detail.customer.satuan }}</p>
                                    <p>{{ detail.customer.alamat }}</p>
                                    <p>{{ detail.customer.provinsi }}</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    Info Produk
                                </div>
                                <div class="card-body" v-if="detail.jenis == 'produk'">
                                    <div class="margin">
                                        <div><small class="text-muted">Nama Produk</small></div>
                                        <div><b>{{ detail.nama }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">Variasi</small></div>
                                        <div>
                                            <b>{{ detail.detail_pesanan_produk[0].gudang_barang_jadi.nama }}</b>
                                        </div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">No SO</small></div>
                                        <div><b>{{ detail.pesanan.so }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">Jumlah</small></div>
                                        <div><b>{{ detail.jumlah }}</b></div>
                                    </div>
                                </div>
                                <div class="card-body" v-else>
                                    <div class="margin">
                                        <div><small class="text-muted">Nama Part</small></div>
                                        <div><b>{{ detail.nama }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">No SO</small></div>
                                        <div><b>{{ detail.no_so }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">Jumlah</small></div>
                                        <div><b>{{ detail.jumlah }}</b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-center font-weight-bold" v-if="detail.jenis == 'produk'">Detail Produk {{
                                        detail.nama }} {{
        detail.detail_pesanan_produk[0].gudang_barang_jadi.nama }}</p>
                                    <p class="text-center font-weight-bold" v-else>Detail Part {{ detail.nama }}</p>
                                    <div class="d-flex flex-row-reverse bd-highlight">
                                        <div class="p-2 bd-highlight">
                                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                                        </div>
                                    </div>
                                    <data-table :headers="headers" :items="hasilPengujian">
                                        <template #header.jumlah_ok>
                                            <div>
                                                <i class="fa fa-check text-success"></i>
                                            </div>
                                        </template>
                                        <template #header.jumlah_nok>
                                            <div>
                                                <i class="fa fa-times text-danger"></i>
                                            </div>
                                        </template>
                                        <template #item.hasil="{ item }">
                                            <div v-html="item.hasil"></div>
                                        </template>
                                    </data-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>