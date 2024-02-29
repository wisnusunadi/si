<script>
export default {
    props: ['header'],
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="p-2">
                    <div class="margin">
                        <button class="btn btn-outline-dark" @click="$router.push({ name: 'permintaanperakitan' })">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </button>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">No Permintaan</small>
                    </div>
                    <div class="margin">
                        <b>{{ header.no_permintaan }}</b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            No BPPB
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.no_bppb }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Tanggal Permintaan
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.tanggal_permintaan }}</b>
                    </div>
                    <div class="margin"><small class="text-muted">
                            Tanggal Akhir Persiapan
                        </small></div>
                    <div class="margin">
                        <b>{{ header.tanggal_akhir_persiapan }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Status Persiapan
                        </small>
                    </div>
                    <div class="margin">
                        <persentase :persentase="header.persentase" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>