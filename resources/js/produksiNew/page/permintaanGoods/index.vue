<script>
import Header from '../../components/header.vue';
import daftarProses from './daftarProses';
import dalamProses from './dalamProses';
import selesaiProses from './selesaiProses';
export default {
    components: { Header, daftarProses, dalamProses, selesaiProses },
    data() {
        return {
            title: 'Permintaan Barang',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Barang',
                    link: '/permintaan-goods'
                }
            ]
        }
    },
}
</script>
<template>
    <div class="body">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Daftar
                            Permintaan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Dalam
                            Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact"
                            type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Selesai</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <daftarProses />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <dalamProses />
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <selesaiProses />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.body {
    font-size: 14px;
}
</style>