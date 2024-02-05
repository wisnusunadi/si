<script>
import loading from '../../../components/loading.vue';
export default {
    props: ['header'],
    components: {
        loading,
    },
    data() {
        return {
            loading: false,
        }
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4>Info Penjualan SPA</h4>
            <div class="row">
                <div class="col-lg-11 col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Customer</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">{{ header.customer }}</b>
                            </div>
                            <div class="margin">
                                {{ header.alamat }}
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">No SO</small>
                                </div>
                                <div><b id="no_so">{{ header.so }}</b></div>
                            </div>
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Status</small>
                                </div>
                                <div id="status" class="align-center">
                                    <loading :persentase="header.status" />
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">No PO</small>
                                </div>
                                <div><b id="no_so">{{ header.po }}</b></div>
                            </div>
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Tanggal PO</small>
                                </div>
                                <div><b id="no_so"> {{ formatDate(header.tgl_po) }} </b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
