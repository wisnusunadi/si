<script>
import axios from 'axios'
export default {
    props: ['produk'],
    data() {
        return {
            search: '',
            searchDetail: '',
            header: [
                { text: 'Nama Produk', value: 'nama' },
                { text: 'Aksi', value: 'action' },
            ],
            dialogDetail: false,
            data_detail: null,
            headersdetail: [
                { text: 'Merk', value: 'merk' },
                { text: 'Nama Produk', value: 'nama'},
                { text: 'No AKD', value: 'no_akd'},
                { text: 'Status', value: 'status'}
            ],
        }
    },

    methods: {
        detailproduk(item) {
            const produk = item.detailproduk
            let updatedDetail = []
            for (let i = 0; i < produk.length; i++) {
                updatedDetail.push({
                    ...produk[i],
                    status: produk[i].status == '1' ? true : false
                })
            }
            this.data_detail = updatedDetail
            this.dialogDetail = true
        },
        async updateStatus(item) {
            const { data } = await axios.post('/api/changeStatusProduk', {
                id: item.id,
                status: item.status
            })

            console.log(data)
        },
        editProduk(item) {
            this.$emit('editProduk', item)
        },
        addProduk() {
            this.$emit('addProduk')
        }
    },
    computed: {
        filteredProduk() {
            return this.produk.filter((item) => {
                const namaMatches = item.nama.toLowerCase().includes(this.search.toLowerCase());
                const detailMatches = item.detailproduk.some((detail) =>
                detail.nama.toLowerCase().includes(this.search.toLowerCase())
                );
                return namaMatches || detailMatches;
            });
        },
    },
    methods: {
        filteredDetailproduk(item) {
            return item.nama.toLowerCase().includes(this.search.toLowerCase());
        },
  },
}
</script>
<template>
    <div>
        <div class="d-flex">
                        <v-card flat class="ml-5">
                            <v-text-field
                            v-model="search"
                            placeholder="Cari Produk"
                            ></v-text-field>
                        </v-card>

                        <v-btn
                        color="primary"
                        class="ml-auto"
                        @click="addProduk"
                        >
                        <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </div>
        <v-data-table
            :headers="header"
            :items="filteredProduk"
            :expanded.sync="expanded"
            show-expand
            single-expand
        >

        <template #expanded-item="{ headers, item }">
            <td :colspan="headers.length">
                <div class="d-flex">
                        <v-card flat class="ml-5">
                            <v-text-field
                            v-model="searchDetail"
                            placeholder="Cari Produk Detail"
                            ></v-text-field>
                        </v-card>
                    </div>
                <v-data-table :headers="headersdetail" :items="item.detailproduk" :search="searchDetail">
                    <template #item.status="{ item }">
                        <v-switch
                            @click="updateStatus(item)"
                            v-model="item.status"
                            :label="item.status ? 'Aktif' : 'Tidak Aktif'"
                        ></v-switch>
                    </template>
            </v-data-table>
            </td>
        </template>

            <template #item.action = "{ item }">
                <div>
                    <v-btn
                        icon
                        @click="editProduk(item)"
                    >
                        <v-icon>
                            mdi-pencil
                        </v-icon>
                    </v-btn> 
                </div>
            </template>

        </v-data-table>

        <v-dialog 
        v-model="dialogDetail"
        max-width="500px"
        persistent
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                    >
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        dark
                        @click="dialogDetail = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    </v-toolbar>

                    <v-card-text>
                        <v-data-table 
                        :headers="headersdetail" 
                        :items="data_detail">

                        <template #item="{ item }">
                            <tr v-if="filteredDetailproduk(item)">
                                <td>{{ item.id }}</td>
                                <td>{{ item.nama }}</td>
                                <td>{{ item.nama_coo }}</td>
                                <td>{{ item.no_akd }}</td>
                                <td>
                                    <v-switch
                                    @click="updateStatus(item)"
                                    v-model="item.status"
                                    :label="item.status ? 'Aktif' : 'Tidak Aktif'"
                                ></v-switch>
                                </td>
                            </tr>
                        </template>
                        </v-data-table>
                    </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>