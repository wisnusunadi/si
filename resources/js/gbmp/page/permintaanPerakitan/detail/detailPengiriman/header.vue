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
                        <button class="btn btn-outline-dark"
                            @click="$router.push({ name: 'permintaan-perakitan-detail', params: { id: $route.params.route } })">
                            <i class="fa fa-arrow-left"></i>
                            Kembali
                        </button>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">No Permintaan</small>
                    </div>
                    <div class="margin"><b>{{ header.no_permintaan }}</b></div>
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
                            Nama Produk
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.nama }}</b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Sudah Transfer
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.sudah_tf }}</b>
                    </div>
                </div>
                <div class="p-2">
                </div>
            </div>
        </div>
    </div>
</template>