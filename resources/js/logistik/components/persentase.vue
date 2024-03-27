<script>
export default {
    props: ['persentase'],
}
</script>
<template>
    <div class="text-center">
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" :style="{ width: persentase + '%' }"
                :aria-valuenow="persentase" aria-valuemin="0" aria-valuemax="100" v-if="persentase > 0">{{ persentase
                }}%
            </div>
            <div class="progress-bar bg-light" role="progressbar" style="width: 100%" aria-valuenow="persentase"
                aria-valuemin="0" aria-valuemax="100" v-else>{{ persentase
                }}%
            </div>
        </div>
        <small class="text-muted">Selesai</small>
    </div>
</template>