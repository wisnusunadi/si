<script>
import axios from 'axios';
import TableSparepart from './table.vue'
export default {
    components: { TableSparepart },
    data() {
        return {
            sparepart: null,
        }
    },
    methods: {
        async getSparepart() {
            try {
                this.$store.dispatch('setLoading', true)
                const { sparepart } = await axios.get('/api/sparepart')
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
    },
    created() {
        this.getSparepart()
    }
}
</script>
<template>
    <v-app>
        <v-main>
            <v-container>
                <v-skeleton-loader v-if="$store.state.loading" class="mx-auto" type="table"></v-skeleton-loader>
                <table-sparepart :part="sparepart" @refresh="getSparepart"></table-sparepart>
            </v-container>
        </v-main>
    </v-app>
</template>