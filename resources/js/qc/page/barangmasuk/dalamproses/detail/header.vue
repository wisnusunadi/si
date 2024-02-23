<script>
export default {
    props: ['header']
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-11 col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">No BPPB</small>
                            </div>
                            <div class="margin">
                                <b>{{ header.bppb }}</b>
                            </div>
                            <div class="margin">
                                <small class="text-muted">Nama Produk</small>
                            </div>
                            <div class="margin">
                                <b>{{ header.produk }}</b>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">Lolos</small>
                            </div>
                            <div class="margin">
                                <span>
                                    <b>{{ header.lolos }} Unit</b>
                                    <b>({{ header.persentase_lolos }}%)</b>
                                </span>
                            </div>
                            <div class="margin">
                                <div class="margin">
                                    <small class="text-muted">Tidak Lolos</small>
                                </div>
                                <span>
                                    <b>{{ header.tidak_lolos }} Unit</b>
                                    <b>({{ header.persentase_tidak_lolos }}%)</b>
                                </span>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="margin">
                                <small class="text-muted">Jumlah</small>
                            </div>
                            <div class="margin">
                                <b>{{ header.jumlah }} Unit</b>
                            </div>
                            <div class="margin">
                                <small class="text-muted">Jumlah Unit Terisi</small>
                            </div>
                            <div class="margin">
                                <b>{{ header.terisi }} Unit</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>