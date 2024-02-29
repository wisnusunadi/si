<script>
export default {
    props: ['persentase'],
}
</script>
<template>
    <div>
        <span class="text-persentase">{{ persentase }} %</span>
        <div class="progress w-100">
            <div class="progress-bar bg-success" role="progressbar" :style="{ width: persentase + '%' }"
                :aria-valuenow="persentase" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>
</template>
<style>
.progress {
    height: 5px;
}

.text-persentase {
    color: #959599;
    font-family: 'Popins', sans-serif;
    font-size: 14px;
    text-align: left;
}
</style>