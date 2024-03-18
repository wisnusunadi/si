<script>
import axios from 'axios';
import TableSparepart from './tablePart.vue'
export default {
    components: { TableSparepart },
    data() {
        return {
            sparepart: [],
        }
    },
    methods: {
        async getSparepart() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get('/api/sparepart')
                this.sparepart = data.map((item) => {
                    return {
                        ...item,
                        kategori: item?.kategori?.nama ?? '-'
                    }
                })
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
                <table-sparepart :part="sparepart" @refresh="getSparepart" v-else></table-sparepart>
            </v-container>
        </v-main>
    </v-app>
</template>