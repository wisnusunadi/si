<script>
import tableRework from './table.vue'
export default {
    components: { tableRework },
    data() {
        return {
            product: null,
        }
    },
}
</script>
<template>
    <v-app>
        <v-main>
            <v-container>
                <v-skeleton-loader
                v-if="$store.state.loading"
                class="mx-auto"
                type="table"
                ></v-skeleton-loader>
                <div v-else>
                    <table-rework
                        :product="product"
                    ></table-rework>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>