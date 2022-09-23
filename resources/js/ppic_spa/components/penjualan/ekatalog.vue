<template >
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title">E-Katalog</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table ekatalogtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Urut</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor AKN</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Buat</th>
                                    <th>Tanggal Edit</th>
                                    <th>Tanggal Delivery</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(penjualanekatalog, idx) in penjualanekatalogs" :key="idx">
                                    <td>{{ idx+1 }}</td>
                                    <td>{{ penjualanekatalog.no_urut }}</td>
                                    <td>{{ penjualanekatalog.so }}</td>
                                    <td v-html="checkdata(akn(penjualanekatalog.no_paket_ppic, penjualanekatalog.status_ppic))"></td>
                                    <td>{{ penjualanekatalog.nopo }}</td>
                                    <td>{{ penjualanekatalog.tgl_buat }}</td>
                                    <td>{{ checkdata(penjualanekatalog.tgl_edit) }}</td>
                                    <td v-html="checkdata(penjualanekatalog.tgl_kontrak)"></td>
                                    <td>{{ penjualanekatalog.nama_customer }}</td>
                                    <td v-html="status(penjualanekatalog.status_ppic)"></td>
                                    <td><button class="button is-info is-light is-small" @click="detail(penjualanekatalog.pesanan.id, penjualanekatalog.status_ppic)">Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import $ from "jquery";
    import mixins from "../../mixins/mix";
    export default {
        mixins: [mixins],
        props: {
            penjualanekatalogs: {
                type: Array,
                required: true
            }
        },
        methods: {
            akn(akn, status){
                switch (status) {
                    case 'batal':
                        return `${akn}<br> <span class="tag is-danger is-light">${status}</span>`
                    case 'negosiasi':
                        return `${akn}<br> <span class="tag is-warning is-light">${status}</span>`
                    case 'draft':
                        return `${akn}<br> <span class="tag is-info is-light">${status}</span>`
                    case 'sepakat':
                        return `${akn}<br> <span class="tag is-success is-light">${status}</span>`
                    default:
                        break;
                }
            },
            checkdata(data){
                if(data == null){
                    return '-'
                }else{
                    return data
                }
            },

        },

        updated() {
            $('.ekatalogtable').DataTable();
        }
    }
    </script>