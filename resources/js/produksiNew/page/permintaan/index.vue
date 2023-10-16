<script>
import Header from '../../components/header.vue'
import Permintaan from './permintaan'
import Riwayat from './riwayat'
import axios from 'axios';
export default {
    components: {
        Header,
        Permintaan,
        Riwayat,
    },
    data() {
        return {
            title: 'Permintaan Perakitan Reworks',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Permintaan Perakitan Reworks',
                    link: '#'
                },
            ],
            permintaan: [],
            riwayat: [],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data:permintaan } = await axios.get('/api/prd/rw/belum_kirim');
                const { data:riwayat } = await axios.get('/api/gbj/rw/riwayat_permintaan')
                this.permintaan = permintaan.map(item => {
                    return {
                        no_urut: `PRD-${item.urutan}`,
                        ...item,
                        tgl_mulai: this.dateFormat(item.tgl_mulai),
                        tgl_selesai: this.dateFormat(item.tgl_selesai),
                    }
                });
                this.riwayat = riwayat.map(item => {
                    return {
                        no_urut: `PRD-${item.urutan}`,
                        ...item,
                        tgl_tf: this.dateFormat(item.tgl_tf),
                    }
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
        }
    },
    mounted() {
        this.getData();
    }
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
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Permintaan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <Permintaan :dataTable="permintaan" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <Riwayat :dataTable="riwayat" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>