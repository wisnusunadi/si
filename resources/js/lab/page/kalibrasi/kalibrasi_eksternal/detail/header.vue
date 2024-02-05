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
                        <span>RSU Pusat Dr. Soetomo</span> <br />
                        <span>Klaten, Kabupaten Klaten</span> <br />
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No Order</span>
                            <br />
                            <b>OR-2021-0001</b>
                        </p>

                        <p>
                            <span class="text-muted">Status</span>
                            <br />
                            <Loading :persentase="header.status" class="w-50" />
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
