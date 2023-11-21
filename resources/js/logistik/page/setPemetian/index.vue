<script>
import Header from '../../components/Header.vue'
import proses from './proses'
import riwayat from './riwayat'
export default {
    components: {
        Header,
        proses,
        riwayat,
    },
    data() {
        return {
            title: 'Set Pemetian',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Pemetian',
                    link: '#'
                }
            ],
            proses: [{
                id: 1,
                no_urut: 'PRD-1',
                nama_produk: 'Produk 1',
                jumlah_selesai: 10,
                jumlah_belum_selesai: 10,
            }],
            riwayat: [],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Produk Reworks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <proses :dataTable="proses" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <riwayat />
                    </div>
                </div>
            </div>
    </div>
</div></template>