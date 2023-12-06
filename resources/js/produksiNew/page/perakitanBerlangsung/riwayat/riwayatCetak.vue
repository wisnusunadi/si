<script>
import DataTable from '../../../components/DataTable.vue';
import axios from 'axios';
export default {
    props: ['riwayat'],
    components: {
        DataTable,
    },
    data() {
        return {
            search: '',
            headers: [
                { text: 'No.', value: 'no' },
                { text: 'Tanggal Cetak', value: 'tgl_cetak' },
                { text: 'Operator', value: 'user' },
                { text: 'Aktivitas', value: 'aktivitas' },
            ],
            dataRiwayatCetak: [],
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalRiwayat').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            })
        },
        async getRiwayatCetak() {
            try {
                this.loading = true;
                const { data } = await axios.get(`/api/prd/fg/riwayat_code/${this.riwayat.id}`)
                this.dataRiwayatCetak = data.detail.map((item, index) => {
                    return {
                        no: index + 1,
                        tgl_cetak: this.dateTimeFormat(item.tgl),
                        ...item
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false;
            }
        }
    },
    created() {
        this.getRiwayatCetak();
    },
}
</script>
<template>
    <div class="modal fade modalRiwayat" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Riwayat Cetak</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="">Nomor Seri</label>
                            <div class="card nomor-so">
                                <div class="card-body">{{ riwayat?.noseri }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="">No BPPB</label>
                            <div class="card nomor-akn">
                                <div class="card-body">{{ riwayat?.no_bppb }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="">Nama Produk</label>
                            <div class="card nomor-po">
                                <div class="card-body">{{ riwayat?.nama }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" v-model="search" class="form-control">
                        </div>
                    </div>

                    <DataTable :headers="headers" :items="dataRiwayatCetak" :search="search" v-if="!loading" />
                    <div class="text-center" v-if="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>