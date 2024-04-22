<script>
import status from '../../../../components/status.vue';
export default {
    props: ['header'],
    components: { status },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="p-2">
                    <div class="margin"><small class="text-muted">No Permintaan</small></div>
                    <div class="margin"><b>{{ header.no_permintaan }}</b></div>
                    <div class="margin"><small class="text-muted">No Referensi</small></div>
                    <div class="margin"><b>{{ header.no_referensi }}</b></div>
                    <div class="margin"><small class="text-muted">Status</small></div>
                    <div class="margin"><b>
                            <status :status="header.status" />
                        </b></div>
                </div>
                <div class="p-2">
                    <div class="margin"><small class="text-muted">Tanggal Permintaan</small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_permintaan) }}</b></div>
                    <div class="margin"><small class="text-muted">Tanggal Pengambilan</small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_pengambilan) }}</b></div>
                </div>
                <div class="p-2">
                    <div class="margin"><small class="text-muted">Durasi</small></div>
                    <div class="margin"><b>{{ header.durasi }}</b></div>
                    <div class="margin"><small class="text-muted">Tanggal Close</small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_close) }}</b></div>
                </div>
            </div>
        </div>
    </div>
</template>