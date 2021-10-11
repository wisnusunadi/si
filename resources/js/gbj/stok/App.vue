<template>
    <div>
        <Show />
        <router-view>
            
        </router-view>
    </div>
</template>

<script>
import Show from './Show.vue'

export default {
    components: {
        Show
    }
}
</script>