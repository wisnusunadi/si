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
            produk: [],
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
                const { data } = await axios.get(`/api/tfp/detail-so/${this.detailSelected.id}`)
                this.produk = data.map(paket => {
                    return {
                        ...paket,
                        persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                        persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                        item: paket.item.map(item => {
                            return {
                                ...item,
                                persentase_belum: this.persentase(paket.jumlah_sisa, paket.jumlah),
                                persentase_sudah: this.persentase(paket.jumlah_gudang, paket.jumlah),
                            }
                        })
                    }
                })
            } catch (error) {
                console.log(error)
            }
        },
        persentase(jmlPerItem, jmlTotal) {
            let item = parseInt(jmlPerItem)
            let total = parseInt(jmlTotal)
            return Math.round((item / total) * 100)
        },
    },
    computed: {
        filterRecursive() {
            const includeSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includeSearch(obj[key], search)
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase())
                    })
                }
                return false
            }
            return this.produk.filter(obj => includeSearch(obj, this.search))
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div>
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
                                <th>Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody v-if="filterRecursive.length > 0">
                            <template v-for="paket in filterRecursive">
                                <tr class="table-dark">
                                    <td colspan="100%">
                                        {{ paket.nama }} <br>
                                        <span class="badge badge-light">Belum Transfer: {{ paket.jumlah_sisa }}
                                            ({{ paket.
            persentase_belum }}%)</span>
                                        <span class="badge badge-warning">
                                            Sudah Transfer: {{ paket.jumlah_gudang }} ({{
            paket.persentase_sudah
        }}%)
                                        </span>
                                    </td>
                                </tr>
                                <tr v-for="item in paket.item" :key="item.id">
                                    <td>
                                        {{ item.variasiSelected.label }}
                                    </td>
                                    <td>{{ item.jumlah }}</td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="100%">Data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filterRecursive"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </section>
            </div>
        </div>
    </div>
</template>