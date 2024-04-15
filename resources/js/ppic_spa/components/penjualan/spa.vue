<template>
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title is-uppercase">{{ jenis }}</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table spatable">
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
                                <tr v-for="(penjualanspa, idx) in penjualanspas" :key="idx">
                                    <td>{{ idx + 1 }}</td>
                                    <td>{{ penjualanspa.pesanan.so }}</td>
                                    <td>{{ penjualanspa.pesanan.no_po }}</td>
                                    <td>{{ penjualanspa.pesanan.tgl_po }}</td>
                                    <td>{{ penjualanspa.customer.nama }}</td>
                                    <td v-html="status(penjualanspa.persentase)"></td>
                                    <td><button class="button is-info is-light is-small"
                                            @click="detail(penjualanspa.pesanan.id, jenis, penjualanspa.persentase)">Detail</button>
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
        penjualanspas: {
            type: Array,
            required: true
        },
        jenis: {
            type: String,
            required: true
        }
    },
    updated() {
        if (this.penjualanspas.length > 0) {
            const table = $('.spatable').DataTable();
            table.destroy();
            $('.spatable').DataTable();
        }
    }
}
</script>