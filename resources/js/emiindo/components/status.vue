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