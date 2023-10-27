<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
export default {
    components: {
        DataTable,
    },
    data() {
        return {
            loading: false,
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Varian',
                    value: 'varian',
                },
                {
                    text: 'No Produk',
                    value: 'no_produk',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            noseri: [],
            loadingData: false,
            formNoseri: [
                {
                    noseri: null,
                }
            ],
            search: '',
            offset: 0,
            limit: 10,
        }
    },
    methods: {
        async getNoseri() {
            try {
                this.loadingData = true
                const { data } = await axios.get('/api/prd/fg/riwayat')
                this.noseri = data.map(item => {
                    return {
                        label: item.noseri,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingData = false
            }
        },
        closeModal() {
            $('.modalTambah').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        onSearch(query) {
            this.search = query;
            this.offset = 0;
        },
    },
    mounted() {
        this.getNoseri()
    },
    computed: {
        noSeriSelect() {
            if (this.noseri.length > 0) {
                let data = this.noseri.map((item) => {
                    return {
                        label: item.noseri,
                        ...item,
                    };
                });
                return data;
            }
        },
        filteredNoSeri() {
            return this.noSeriSelect.filter((item, idx) =>
                Object.values(item).some(
                    (val) =>
                        val != null &&
                        val.toString().toLowerCase().includes(this.search.toLowerCase())
                )
            );
        },
        paginated() {
            return this.filteredNoSeri.slice(
                this.offset,
                this.limit + this.offset
            );
        },
        hasNextPage() {
            const nextOffset = this.offset + this.limit;
            return Boolean(
                this.filteredNoSeri.slice(nextOffset, this.limit + nextOffset)
                    .length
            );
        },
        hasPrevPage() {
            const prevOffset = this.offset - this.limit;
            return Boolean(
                this.filteredNoSeri.slice(prevOffset, this.limit + prevOffset)
                    .length
            );
        },
    }
}
</script>
<template>
    <div class="modal fade modalTambah" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Penggantian Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary mb-1" @click="formNoseri.push({ noseri: null })">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="spinner-border" role="status" v-if="loadingData">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="scrollable" v-else>
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>No Seri</th>
                                    <th>Nama Produk</th>
                                    <th>Varian</th>
                                    <th>No Produk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in formNoseri" :key="index">
                                    <td>
                                        <v-select :options="paginated" v-model="item.noseri" @search="onSearch">
                                            <li slot="list-footer" class="pagination">
                                                <button type="button" class="btn btn-secondary" :disabled="!hasPrevPage"
                                                    @click="offset -= limit">
                                                    Prev
                                                </button>
                                                <button type="button" class="btn btn-primary" :disabled="!hasNextPage"
                                                    @click="offset += limit">
                                                    Next
                                                </button>
                                            </li>
                                        </v-select>
                                    </td>
                                    <td>
                                        {{ item.noseri?.nama ?? '-' }}
                                    </td>
                                    <td>
                                        {{ item.noseri?.varian ?? '-' }}
                                    </td>
                                    <td>
                                        {{ item.noseri?.no_produk ?? '-' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="formNoseri.splice(index, 1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-success" :disabled="loading">Transfer</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.pagination {
    display: flex;
    margin: 0.25rem 0.25rem 0;
}

.pagination button {
    flex-grow: 1;
}

.pagination button:hover {
    cursor: pointer;
}

.scrollable {
    height: 500px;
    overflow-y: auto;
}
</style>