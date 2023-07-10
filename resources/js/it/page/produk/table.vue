<script>
import axios from 'axios'
export default {
    props: ['produk'],
    data() {
        return {
            search: '',
            header: [
                { text: 'Nama Produk', value: 'nama' },
                { text: 'Detail Produk', value: 'detail'},
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
    }
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
            :search="search"
            :headers="header"
            :items="produk"
        >
            <template #item.detail="{ item }">
                <div>
                    <v-btn
                        color="primary"
                        @click="detailproduk(item)"
                    >
                        Detail
                    </v-btn>
                </div>
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
                            
                            <template #item.status="{ item }">
                                <v-switch
                                    @click="updateStatus(item)"
                                    v-model="item.status"
                                    :label="item.status ? 'Aktif' : 'Tidak Aktif'"
                                ></v-switch>
                            </template>
                        </v-data-table>
                    </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>