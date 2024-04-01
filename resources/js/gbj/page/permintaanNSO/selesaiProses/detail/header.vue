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
                        <b>{{ header.durasi }}</b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Tanggal Selesai Proses
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ dateFormat(header.tgl_close) }}</b>
                    </div>
                </div>
                <div class="p-2">
                    <div class="margin">
                        <small class="text-muted">
                            Nama Bagian
                        </small>
                    </div>
                    <div class="margin">
                        <b>{{ header.nama_bagian }}</b>
                    </div>
                    <div class="margin"><small class="text-muted">
                            Tujuan Permintaan
                        </small></div>
                    <div class="margin">
                        <b>{{ header.tujuan_permintaan }}</b>
                    </div>
                </div>
            </div>
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
        </div>
    </div>
</template>