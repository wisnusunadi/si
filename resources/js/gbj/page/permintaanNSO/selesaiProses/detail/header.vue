<script>
import status from '../../../../components/status.vue'
export default {
    components: { status },
    props: ['header'],
    methods: {
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Tanggal Close Hari Ini',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
    },
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
                    <div class="margin" v-if="!$route.params.selesai && $route.params.jenis == 'Peminjaman'">
                        <small class="text-muted">
                            Durasi
                        </small>
                    </div>
                    <div class="margin" v-if="!$route.params.selesai && $route.params.jenis == 'Peminjaman'">
                        <b>{{ header.durasi }} <span class="text-danger" v-if="header.lebih_durasi > 0"> + {{
                            header.lebih_durasi }} Hari</span></b>
                    </div>
                    <div class="margin">
                        <small class="text-muted">
                            Tanggal Close
                        </small>
                    </div>
                    <div class="margin">
                        <b :class="calculateDateFromNow(header.tgl_close).color">{{
                            dateFormat(header.tgl_close) }}</b> <br>
                        <small :class="calculateDateFromNow(header.tgl_close).color"
                            v-if="!$route.params.selesai && $route.params.jenis == 'Peminjaman'">
                            <i :class="calculateDateFromNow(header.tgl_close).icon"></i>
                            {{ calculateDateFromNow(header.tgl_close).text }}
                        </small>
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