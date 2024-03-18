<script>
import pagination from '../../components/pagination.vue';
import axios from 'axios';
export default {
    components: {
        pagination
    },
    props: ['showModal', 'detailSelected'],
    data() {
        return {
            renderPaginate: [],
            items: [],
            search: '',
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            this.$emit('close');
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/v2/gbj/get_detail_rekap_so_produk/${this.detailSelected.id}`)
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    created() {
        this.getData()
    },
    computed: {
        filterDalamProses() {
            return this.items.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    }
}
</script>
<template>
    <div class="modal" :class="{ 'is-active': showModal }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title"></p>
                <button class="delete" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <table class="table is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered" rowspan="2">No</th>
                            <th class="has-text-centered" colspan="2">Nomor</th>
                            <th class="has-text-centered" rowspan="2">Customer</th>
                            <th class="has-text-centered" colspan="2">Jumlah</th>
                            <th class="has-text-centered" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Sales Order</th>
                            <th>Purchase Order</th>
                            <th>Pesanan</th>
                            <th>Terkirim</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr v-for="(item, index) in renderPaginate" :key="index">
                            <td>{{ item.no }}</td>
                            <td>{{ item.so }}</td>
                            <td>{{ item.no_po }}</td>
                            <td>{{ item.customer }}</td>
                            <td>{{ item.count_pesanan }}</td>
                            <td>{{ item.count_transfer }}</td>

                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="100%" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
                <pagination :filteredDalamProses="filterDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </section>
        </div>
    </div>
</template>