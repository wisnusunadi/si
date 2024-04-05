<script>
import status from '../../../../components/status.vue'
export default {
    components: { status },
    props: ['header'],
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Nama
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.nama }}</b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Bagian
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.bagian }}</b>
                    </div>
                    <div class="margin"><small class="text-muted">
                            Tujuan Permintaan
                        </small></div>
                    <div class="margin">
                        <b>{{ header.tujuan_permintaan }}</b>
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
                            No Referensi
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.no_referensi }}</b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Status
                        </small>
                    </div>
                    <div class="margin">
                        <status :status="header.status" />
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Tanggal Permintaan
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.tgl_permintaan }}</b>
                    </div>
                    <div class="margin"><small class="text-muted">
                            Tanggal Pengambilan
                        </small></div>
                    <div class="margin">
                        <b>{{ dateFormat(header.tgl_ambil) }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Durasi
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.durasi }} <span class="text-danger" v-if="header.lebih_durasi > 0"> + {{
                            header.lebih_durasi }} Hari</span></b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Tanggal Close
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ dateFormat(header.tgl_close) }}</b>
                    </div>
                    <div class="margin" v-if="$route.params.selesai">
                        <small class="text-muted">
                            Dokumen FPBJ
                        </small>
                    </div>
                    <div class="margin" v-if="$route.params.selesai">
                        <button class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-print"></i> Cetak FPBJ
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between" v-if="$route.params.selesai">
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Keterangan
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.ket }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Catatan
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.ket }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Alasan
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.ket }}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>