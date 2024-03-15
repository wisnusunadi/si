<script>
import Header from '../../../../components/header.vue';
import FormPermintaan from './form.vue';
export default {
    components: {
        Header,
        FormPermintaan
    },
    data() {
        return {
            title: 'Form Transfer Permintaan Perakitan',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/gbmp/perakitan'
                },
                {
                    name: 'Detail Permintaan Perakitan',
                    link: `/gbmp/perakitan/${this.$route.params.id}`
                },
                {
                    name: 'Form Transfer Permintaan Perakitan',
                    link: '/permintaan-perakitan/detail/transfer'
                }
            ],
            header: {
                id: 1,
                no_permintaan: '20210621001',
                no_bppb: '20210621001',
                tgl_permintaan: '2023-09-23',
                tanggal_permintaan: '23 September 2023',
                tgl_akhir: '2023-10-23',
                tanggal_akhir_persiapan: '23 Oktober 2023',
                persentase: 25
            },
            permintaanProduk: [
                {
                    no: 1,
                    nama: 'Produk 1',
                    jumlah: 10,
                    sudah_transfer: 5
                },
                {
                    no: 2,
                    nama: 'Produk 2',
                    jumlah: 20,
                    sudah_transfer: 10
                },
                {
                    no: 3,
                    nama: 'Produk 3',
                    jumlah: 30,
                    sudah_transfer: 15
                }
            ]
        }
    },
    methods: {
        mergePermintaan(input) {
            const index = this.permintaanProduk.findIndex((item) => item.no === input.no);
            this.permintaanProduk[index] = input;
        },
        transfer() {
            // pilih produk yang memiliki object key permintaan dan value lebih dari 0
            const produk = this.permintaanProduk.filter((item) => item?.permintaan?.length > 0);
            console.log(produk);
        }

    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">No Permintaan</small>
                            </div>
                            <div class="margin">
                                <b>{{ header.no_permintaan }}</b>
                            </div>
                            <div class="margin">
                                <small class="text-muted">
                                    No BPPB
                                </small>
                            </div>
                            <div class="margin">
                                <b>{{ header.no_bppb }}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">
                                    Tanggal Permintaan
                                </small>
                            </div>
                            <div class="margin">
                                <b>{{ header.tanggal_permintaan }}</b>
                            </div>
                            <div class="margin"><small class="text-muted">
                                    Tanggal Akhir Persiapan
                                </small></div>
                            <div class="margin">
                                <b>{{ header.tanggal_akhir_persiapan }}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">
                                    Status Persiapan
                                </small>
                            </div>
                            <div class="margin">
                                <persentase :persentase="header.persentase" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div v-for="(produk, key) in permintaanProduk" :key="key">
                    <FormPermintaan :item="produk" @inputProduk="mergePermintaan" />
                </div>
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-secondary"
                            @click="$router.push(`/gbmp/perakitan/${$route.params.id}`)">Batal</button>
                    </div>
                    <div class="p-2 bd-highlight" @click="transfer">
                        <button class="btn btn-success">
                            Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>