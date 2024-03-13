<script>
import Header from '../../../../components/header.vue';
import Table from './table.vue';
export default {
    components: {
        Header,
        Table
    },
    data() {
        return {
            title: 'Form Transfer Permintaan Perakitan',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/gbmp/perakitan'
                },
                {
                    name: 'Detail Permintaan Perakitan',
                    link: `/gbmp/perakitan/${this.$route.params.id}`
                },
                {
                    name: 'Form Transfer Permintaan Perakitan',
                    link: '/permintaan-perakitan/detail/transfer'
                }
            ],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div v-for="i in 3" :key="i">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">Produk {{ i }}</div>
                        <div class="p-2 bd-highlight"><button class="btn btn-primary">Tambah</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>