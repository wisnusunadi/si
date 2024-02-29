<script>
export default {
    props: {
        persentase: {
            type: Number,
            required: true,
            default: 0
        }
    },
}
</script>
<template>
    <div>
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
    </div>
</template>