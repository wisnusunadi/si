<script>
import axios from 'axios'
export default {
    props: ['selectCategory', 'dialogCreate', 'kategori'],
    data() {
        return {
            header: [
                { text: 'Kode Kategori', value: 'kode'},
                { text: 'Nama Kategori', value: 'nama' },
                { text: 'Action', value: 'action', sortable: false}
            ],
            valid: true,
            rules: {
                required: value => !!value || 'Required.',
                unique: (id, nama) => {
                    return id ? true :
                    !this.kategori.some(item => item.nama === nama) || 'Nama Kategori sudah ada'
                }
            },
        }
    },
    methods: {
        closeDialog() {
            this.$emit('closeDialog')
            this.$emit('getCategory')
        },
        async simpan() {
            const isValid = await this.$refs.formCategory.validate()
            if(!isValid) return
            try {
                const { data } = await axios.post('/api/kategori', this.selectCategory)
                this.$swal('Berhasil', 'Kategori berhasil ditambahkan', 'success')
                this.closeDialog()
            } catch (error) {
                console.log(error)
                this.$swal('Gagal', 'Kategori gagal ditambahkan', 'error')
            }
        },
        tambah() {
            this.selectCategory.push({
                kode: '',
                nama: '',
            })
        },
        removeCategory(item) {
            const index = this.selectCategory.indexOf(item)
            this.selectCategory.splice(index, 1)
        }
    },
}
</script>
<template>
    <div>
        <v-dialog
            v-model="dialogCreate"
            persistent
            max-width="70%"
        >
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Tambah Kategori</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn text @click="simpan">Simpan</v-btn>
                </v-toolbar>

                <v-form ref="formCategory" v-model="valid" lazy-validation>
                    <v-data-table
                        :headers="header"
                        :items="selectCategory"
                    >

                        <template #top>
                            <div class="d-flex justify-end">
                                <v-btn class="mr-1 mt-1" color="primary" @click="tambah">Tambah</v-btn>
                            </div>
                        </template>

                        <template #item.kode = "{ item }">
                            <v-text-field
                                class="mt-5"
                                v-model="item.kode"
                                label="Kode Kategori"
                                outlined
                                dense
                            ></v-text-field>
                        </template>

                        <template #item.nama = "{ item }">
                            <v-text-field
                                class="mt-5"
                                v-model="item.nama"
                                label="Nama Kategori"
                                outlined
                                dense
                                @input="item.nama = item.nama.toUpperCase()"
                                :rules="[rules.required, rules.unique(item.id, item.nama)]"
                            ></v-text-field>
                        </template>

                        <template #item.action = "{ item }">
                            <v-btn
                                v-if="!item.id"
                                color="red"
                                text
                                @click="removeCategory(item)"
                            >
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>