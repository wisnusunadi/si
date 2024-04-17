<script>
import status from '../../../../../components/status.vue';
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
                    <div class="margin"><small class="text-muted">No Permintaan</small></div>
                    <div class="margin"><b>{{ header.no_permintaan }}</b></div>
                    <div class="margin"><small class="text-muted">
                            No Referensi
                        </small></div>
                    <div class="margin"><b>{{ header.tujuan_permintaan }}</b></div>
                    <div class="margin"><small class="text-muted">
                            Tujuan Permintaan
                        </small></div>
                    <div class="margin"><b>{{ header.tujuan_permintaan }}</b></div>
                </div>
                <div class="p-2">
                    <div class="margin"><small class="text-muted">
                            Tanggal Permintaan
                        </small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_permintaan) }}</b></div>
                    <div class="margin"><small class="text-muted">
                            Tanggal Kebutuhan
                        </small></div>
                    <div class="margin"><b>{{ dateFormat(header.tgl_kebutuhan)
                            }}</b></div>
                    <div v-if="header.alasan">
                        <div class="margin"><small class="text-muted">
                                Alasan Ditolak
                            </small></div>
                        <div class="margin"><b>{{ header.alasan
                                }}</b></div>
                    </div>
                </div>
                <div class="p-2">
                    <div v-if="header.jenis == 'peminjaman'">
                        <div class="margin"><small class="text-muted">
                                Durasi
                            </small></div>
                        <div class="margin"><b>
                                {{ header.durasi }}
                            </b></div>
                        <div class="margin"><small class="text-muted">
                                Tanggal Pengembalian
                            </small></div>
                        <div class="margin">
                            <b :class="calculateDateFromNow(header.tgl_close).color">{{
                                dateFormat(header.tgl_close) }}</b> <br>
                            <small :class="calculateDateFromNow(header.tgl_close).color"
                                v-if="header.jenis == 'peminjaman'">
                                <i :class="calculateDateFromNow(header.tgl_close).icon"></i>
                                {{ calculateDateFromNow(header.tgl_close).text }}
                            </small>
                        </div>
                    </div>
                    <div class="margin"><small class="text-muted">
                            Status
                        </small></div>
                    <div class="margin">
                        <status :status="header.status" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>