<script>
import status from '../../../../../components/status.vue';
export default {
    props: ['header'],
    components: { status },
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
    }
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
                    <div class="margin"><small class="text-muted">Tujuan Permintaan</small></div>
                    <div class="margin"><b>{{ header.tujuan_permintaaan }}</b></div>
                </div>
                <div class="p-2">
                    <div class="margin"><small class="text-muted">Tanggal Permintaan</small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_permintaan) }}</b></div>
                    <div class="margin"><small class="text-muted">Tanggal Pengambilan</small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_pengambilan) }}</b></div>
                    <div class="margin"><small class="text-muted">Status</small></div>
                    <div class="margin"><b>
                            <status :status="$route.params.status" />
                        </b></div>
                </div>
                <div class="p-2">
                    <div class="margin" v-if="$route.params.jenis == 'peminjaman'"><small
                            class="text-muted">Durasi</small></div>
                    <div class="margin" v-if="$route.params.jenis == 'peminjaman'"><b>{{ header.durasi }}</b></div>
                    <div class="margin"><small class="text-muted">Tanggal Close</small></div>
                    <div class="margin" v-if="$route.params.jenis == 'peminjaman'">
                        <b :class="calculateDateFromNow(header.tgl_close).color">{{
                            dateFormat(header.tgl_close) }}</b> <br>
                        <small :class="calculateDateFromNow(header.tgl_close).color">
                            <i :class="calculateDateFromNow(header.tgl_close).icon"></i>
                            {{ calculateDateFromNow(header.tgl_close).text }}
                        </small>
                    </div>
                    <div class="margin" v-else>
                        <b>{{ dateFormat(header.tgl_close) }}</b>
                    </div>
                    <div class="margin" v-if="$route.params.selesai">
                        <small class="text-muted">Dokumen BPBJ</small>
                    </div>
                    <div class="margin" v-if="$route.params.selesai">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-print"></i>
                            Cetak BPBJ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>