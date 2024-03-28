<script>
export default {
    props: ['status'],
    methods: {
        getStatus(status) {
            // text capitalize
            status = status.charAt(0).toUpperCase() + status.slice(1)
            switch (status) {
                case 'Sepakat':
                    return {
                        text: status,
                        color: 'badge-success'
                    }
                case 'Negosiasi':
                    return {
                        text: status,
                        color: 'badge-warning'
                    }
                case 'Batal':
                    return {
                        text: status,
                        color: 'badge-danger'
                    }
                case 'Penjualan':
                    return {
                        text: status,
                        color: 'red-text'
                    }
                case 'PO':
                    return {
                        text: status,
                        color: 'purple-text'
                    }
                case 'Gudang':
                    return {
                        text: status,
                        color: 'orange-text'
                    }
                case 'QC':
                    return {
                        text: status,
                        color: 'yellow-text'
                    }
                case 'Terkirim Sebagian':
                    return {
                        text: status,
                        color: 'blue-text'
                    }
                case 'Kirim':
                    return {
                        text: status,
                        color: 'green-text'
                    }
                default:
                    return {
                        text: status,
                        color: 'badge-info'
                    }
            }
        }
    },
}
</script>
<template>
    <div>
        <span :class="['badge', getStatus(status).color]">{{ getStatus(status).text }}</span>
    </div>
</template>