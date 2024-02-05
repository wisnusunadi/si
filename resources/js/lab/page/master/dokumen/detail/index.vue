<script>
import noorder from "./noorder.vue";
import produk from "./produk.vue";
export default {
    components: {
        noorder,
        produk,
    },
    data() {
        return {
            search: "",
        };
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4>Riwayat Pemakaian Dokumen SPA-MK/LAB-03PBK</h4>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link active"
                        id="pills-order-tab"
                        data-toggle="pill"
                        data-target="#pills-order"
                        type="button"
                        role="tab"
                        aria-controls="pills-order"
                        aria-selected="true"
                    >
                        No Order
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="pills-produk-tab"
                        data-toggle="pill"
                        data-target="#pills-produk"
                        type="button"
                        role="tab"
                        aria-controls="pills-produk"
                        aria-selected="false"
                    >
                        Produk
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div
                    class="tab-pane fade show active"
                    id="pills-order"
                    role="tabpanel"
                    aria-labelledby="pills-order-tab"
                >
                    <noorder />
                </div>
                <div
                    class="tab-pane fade"
                    id="pills-produk"
                    role="tabpanel"
                    aria-labelledby="pills-produk-tab"
                >
                    <produk />
                </div>
            </div>
        </div>
    </div>
</template>
