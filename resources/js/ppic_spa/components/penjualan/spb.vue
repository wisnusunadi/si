<template>
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title is-uppercase">{{ jenis }}</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table spbtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(penjualanspb, idx) in penjualanspbs" :key="idx">
                                    <td>{{ idx + 1 }}</td>
                                    <td>{{ penjualanspb.pesanan.so }}</td>
                                    <td>{{ penjualanspb.pesanan.no_po }}</td>
                                    <td>{{ penjualanspb.pesanan.tgl_po }}</td>
                                    <td>{{ penjualanspb.customer.nama }}</td>
                                    <td v-html="status(penjualanspb.persentase)"></td>
                                    <td><button class="button is-info is-light is-small"
                                            @click="detail(penjualanspb.pesanan.id, jenis, penjualanspb.persentase)">Detail</button>
                                    </td>
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
import mix from '../../mixins/mix';
import $ from "jquery";
export default {
    mixins: [mix],
    props: {
        penjualanspbs: {
            type: Array,
            required: true
        },
        jenis: {
            type: String,
            required: true
        }
    },
    updated() {
        if (this.penjualanspbs.length > 0) {
            const table = $('.spbtable').DataTable();
            table.destroy();
            $('.spbtable').DataTable();
        }
    }
}
</script>