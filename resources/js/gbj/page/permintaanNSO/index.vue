<script>
import Header from '../../components/header.vue'
import permintaan from '../permintaanNSO/nso.vue'
export default {
    components: {
        Header,
        permintaan
    },
    data() {
        return {
            title: 'Permintaan Selain Sales Order',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Selain Sales Order',
                    link: '/permintaan-nso'
                }
            ],
            permintaanNSO: [],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <permintaan :permintaan="permintaanNSO" />
            </div>
        </div>
    </div>
</template>