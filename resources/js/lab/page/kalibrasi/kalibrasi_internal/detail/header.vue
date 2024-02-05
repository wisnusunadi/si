<script>
import Loading from "../../../../components/loading.vue";
export default {
    components: {
        Loading,
    },
    props: ["header"],
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h2>Info Penjualan</h2>
            <div class="card-text">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight">
                        <p class="text-muted">Customer</p>
                        <b>{{ header.customer }}</b> <br />
                        <span v-if="header.jenis_pemilik.label">{{ JSON.parse(JSON.stringify(header.jenis_pemilik?.label)) }}</span> <br v-if="header.jenis_pemilik.label">
                        <span>{{ header.nama }}</span> <br />
                        <span class="d-inline-block text-truncate" style="max-width: 150px;">{{ header.alamat }}</span> <br />
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No Order</span>
                            <br />
                            <b>{{ header.no_order }}</b>
                        </p>

                        <p>
                            <span class="text-muted">Status</span>
                            <br />
                            <Loading :persentase="header.status" />
                        </p>
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No SO</span>
                            <br />
                            <b>{{ header.so }}</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.progress {
    width: 50%;
}
</style>
