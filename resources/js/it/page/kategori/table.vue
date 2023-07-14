<script>
import Modal from './modalCreateEdit.vue'
import axios from 'axios'
export default {
    components: { Modal },
    props: ['kategori'],
    data() {
        return {
            search: '',
            header: [
                { text: 'id', value: 'id'},
                { text: 'Kode Kategori', value: 'kode'},
                { text: 'Nama Kategori', value: 'nama' },
            ],
            selectAll: false,
            selectCategory: [],
            showDialog: false,
        }
    },
    methods: {
        checkAll() {
            if (this.selectAll) {
                this.selectCategory = [...this.kategori]
            } else {
                this.selectCategory = [];
            }
        },
        getCategory() {
            this.$emit('getCategory')
        },
        closeDialog() {
            this.showDialog = false
        },
        async deleteCategory() {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                try {
                    const { data } = await axios.delete('/api/kategori', {
                        data: this.selectCategory
                    })
                    this.$emit('getCategory')
                    this.$swal('Berhasil', 'Kategori berhasil dihapus', 'success')
                } catch (error) {
                    console.log(error)
                    this.$swal('Gagal', 'Kategori gagal dihapus', 'error')
                }
            })
        }
    },
}
</script>
<template>
    <div>
        <Modal 
            :selectCategory="selectCategory" 
            :dialogCreate="showDialog"
            :kategori="kategori"
            @getCategory="getCategory"
            @closeDialog="closeDialog">
        </Modal>

        <div class="d-flex">
            <v-card flat class="ml-5 mr-auto">
                <v-text-field
                v-model="search"
                placeholder="Cari Kategori"
                ></v-text-field>
            </v-card>
            <v-card flat>
                <v-btn color="primary" @click="showDialog = true">
                    Tambah atau Edit Produk
                </v-btn>
                <v-btn color="error" v-if="selectCategory.length" @click="deleteCategory">
                    Hapus Produk
                </v-btn>
            </v-card>
        </div>
        <v-data-table
        hide-default-header
        :headers="header"
        :items="kategori"
        :search="search"
        >
                <!-- No Data -->
        <template #no-data>
            <div class="d-flex justify-center">
                <v-btn color="primary" @click="getCategory">Refresh</v-btn>
            </div>
        </template>

            <template #header>
                <thead>
                    <tr>
                    <th class="text-left">
                        <v-checkbox
                            :indeterminate="selectCategory.length > 0 && selectCategory.length < kategori.length"
                            @click.native="checkAll"
                            v-model="selectAll"
                        ></v-checkbox>
                    </th>
                    <th class="text-left">
                        Kode Kategori
                    </th>
                    <th class="text-left">
                        Nama Kategori
                    </th>
                </tr>
                </thead>
            </template>

            <template #item.id = "{ item }">
                <v-checkbox
                    v-model="selectCategory"
                    :value="item"
                ></v-checkbox>
            </template>
        </v-data-table>
    </div>
</template>