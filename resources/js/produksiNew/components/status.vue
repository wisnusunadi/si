<script>
export default {
    props: ['status'],
    methods: {
        statusProses(status) {
            switch (status) {
                case 'menunggu':
                    return 'badge-warning'
                    break;
            
                default:
                    return 'badge-primary'
                    break;
            }
        },
        statusText(status) {
            switch (status) {
                case 'menunggu':
                    return 'Menunggu Proses Gudang'
                    break;
            
                default:
                    return 'Permintaan Baru'
                    break;
            }
        }
    },
}
</script>
<template>
    <div>
        <span class="badge" :class="statusProses(status)">{{ statusText(status) }}</span>
    </div>
</template>