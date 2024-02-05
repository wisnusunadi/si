<script>
import Loading from "../../../components/loading.vue";
export default {
    props: ['header'],
    components: {
        Loading,
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h2>Info</h2>
            <div class="card-text">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">Distributor</span><br />
                            <b>{{ header.customer }}</b> <br />
                            <span>{{ header.jenis_pemilik }}</span> <br />
                            <span>{{ header.nama }}</span> <br />
                            <span>{{ header.alamat }}</span> <br />
                        </p>
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">No Order</span><br />
                            <b>{{ header.no_order }}</b>
                        </p>
                        <p>
                            <span class="text-muted">No SO</span><br />
                            <b>{{ header.so }}</b>
                        </p>
                    </div>
                    <div class="p-2 flex-fill bd-highlight">
                        <p>
                            <span class="text-muted">Status</span><br />
                            <Loading :persentase="header.status" />
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
