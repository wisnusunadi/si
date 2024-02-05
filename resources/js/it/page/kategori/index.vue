<script>
import axios from 'axios';
import TableCategory from './table.vue'
export default {
    components: { TableCategory },
    data() {
        return {
            category: null,
        }
    },
    methods: {
        async getCategory() {
            try {
                this.$store.dispatch('setLoading', true)
                const { kategori } = await axios.get('/api/kategori').then(res => res.data)
                this.category = kategori
                this.$store.dispatch('setLoading', false)
            } catch (error) {
                console.log(error)
            }
        },
    },
    created() {
        this.getCategory()
    }
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
                        <table-category 
                            :kategori="category"
                            @getCategory="getCategory"
                        ></table-category>
                        </div>


            </v-container>
        </v-main>
    </v-app>
</template>