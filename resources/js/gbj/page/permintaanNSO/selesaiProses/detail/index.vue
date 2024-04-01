<script>
import Header from '../../../../components/header'
import headerDetail from './header.vue'
export default {
    components: {
        Header,
        headerDetail
    },
    data() {
        return {
            title: 'Detail Permintaan Selain Sales Order',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Selain Sales Order',
                    link: '/gbj/nso'
                },
                {
                    name: 'Detail Permintaan Selain Sales Order',
                    link: '#'
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail />
    </div>
</template>