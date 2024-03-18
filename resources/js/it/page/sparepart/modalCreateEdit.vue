<script>
import axios from 'axios'

export default {
    props: ['selectPart', 'dialogCreate', 'part'],
    data() {
        return {
            header: [
                {
                    text: 'Kategori',
                    value: 'kelompok_produk_id',
                },
                {
                    text: 'Kode',
                    value: 'kode',
                },
                {
                    text: 'Nama',
                    value: 'nama',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            category: null,
            kelompok: [
                { text: 'Alat Kesehatan', value: 1 },
                { text: 'Water Treatment', value: 2 },
                { text: 'Aksesoris', value: 3 },
                { text: 'Lain lain', value: 4 },
                { text: 'Sparepart', value: 5 }
            ],
            valid: true,
            rules: {
                required: value => !!value || 'Required.',
                nameUnique: (id, value) => {
                    return id ? true : !this.part.some(item => item.nama === value) || 'Nama part sudah ada'
                },
                kodeUnique: (id, value) => {
                    return id ? true : !this.part.some(item => item.kode === value) || 'Kode part sudah ada'
                }
            },
            loading: false,
        }
    },
    methods: {
        tambah() {
            this.selectPart.push({
                kelompok_produk_id: '',
                kode: '',
                nama: '',
            })
        },
        async simpan() {
            const isValid = this.$refs.formProducts.validate()
            if (!isValid) return

            try {
                this.loading = true
                const { data } = await axios.post('/api/sparepart', this.selectPart)
                this.$emit('refresh')
                this.closeDialog()
                this.$swal('Berhasil', 'Sparepart berhasil ditambahkan', 'success')
            } catch (error) {
                this.$swal('Gagal', 'Sparepart gagal ditambahkan', 'error')
            } finally {
                this.loading = false
            }
        },
        closeDialog() {
            this.$emit('closeDialog')
        },
        removeProduk(item) {
            const index = this.selectPart.indexOf(item)
            this.selectPart.splice(index, 1)
        }
    },
}
</script>
<template>
    <div>
        <v-dialog v-model="dialogCreate" persistent max-width="70%">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Tambah Part</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn @click="simpan" :loading="loading" :disabled="loading" text>Simpan</v-btn>
                </v-toolbar>

                <v-form ref="formProducts" v-model="valid" lazy-validation>
                    <v-data-table :headers="header" :items="selectPart">
                        <template #top>
                            <div class="d-flex justify-end">
                                <v-btn class="mr-1 mt-1" color="primary" @click="tambah">Tambah</v-btn>
                            </div>
                        </template>

                        <template #item.kelompok_produk_id="{ item }">
                            <v-autocomplete class="mt-5" v-model="item.kelompok_produk_id" :items="kelompok"
                                :rules="[rules.required]" outlined dense></v-autocomplete>
                        </template>

                        <template #item.kode="{ item }">
                            <v-text-field class="mt-5" v-model="item.kode"
                                :rules="[rules.required, rules.kodeUnique(item.id, item.kode)]" outlined dense
                                @input="item.kode = item.kode.toUpperCase()"></v-text-field>
                        </template>

                        <template #item.nama="{ item }">
                            <div>
                                <v-text-field class="mt-5" v-model="item.nama"
                                    :rules="[rules.required, rules.nameUnique(item.id, item.nama)]" outlined dense
                                    @input="item.nama = item.nama.toUpperCase()"></v-text-field>
                            </div>
                        </template>

                        <template #item.aksi="{ item }">
                            <v-btn icon @click="removeProduk(item)" v-if="!item.id">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>