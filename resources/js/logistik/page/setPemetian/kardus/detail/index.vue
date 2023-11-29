<script>
import Header from '../../../../components/header.vue';
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        Header,
        DataTable
    },
    data() {
        return {
            title: 'Detail Set Kardus',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Kardus',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No Seri', value: 'no_seri' },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat' },
            ]
        }
    },
}
</script>
<template>
    <div>
        
    </div>
</template>