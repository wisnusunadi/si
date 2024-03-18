<script>
import axios from 'axios'
import Modal from './modalCreateEdit.vue'
export default {
    components: { Modal },
    props: ['part'],
    data() {
        return {
            search: '',
            header: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Kategori',
                    value: 'kategori',
                },
                {
                    text: 'Kode',
                    value: 'kode',
                },
                {
                    text: 'Nama',
                    value: 'nama',
                }
            ],
            showDialog: false,
            selectPart: [],
            selectAll: false,
        }
    },
    methods: {
        async deletePart() {
            this.$swal({
                text: `Yakin ingin menghapus ${this.selectPart.length} produk?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                {
                    try {
                        const { data } = await axios.delete('/api/sparepart', {
                            data: this.selectPart
                        })
                        this.$swal('Berhasil', 'Sparepart berhasil dihapus', 'success')
                        this.$emit('refresh')
                    } catch (error) {
                        this.$swal('Gagal', 'Sparepart gagal dihapus', 'error')
                    }
                } 
            })
        },
        checkAll() {
            if (this.selectAll) {
                this.selectPart = [...this.part]
            } else {
                this.selectPart = []
            }
        },
        refresh() {
            this.$emit('refresh')
        },
    },
}
</script>
<template>
    <div>
        <Modal @closeDialog="showDialog = false" @refresh="refresh" v-if="showDialog" :dialogCreate="showDialog"
            :part="part" :selectPart="selectPart"></Modal>
        <div class="d-flex">
            <v-card flat class="ml-5 mr-auto">
                <v-text-field v-model="search" placeholder="Cari Sparepart"></v-text-field>
            </v-card>
            <v-card flat>
                <v-btn color="primary" @click="showDialog = true">
                    Tambah atau Edit Sparepart
                </v-btn>
                <v-btn color="error" v-if="selectPart.length" @click="deletePart">
                    Hapus Sparepart
                </v-btn>
            </v-card>
        </div>
        <v-data-table :headers="header" :items="part" :search="search">
            <template #no-data>
                <div class="d-flex justify-center">
                    <v-btn color="primary" @click="refresh">Refresh</v-btn>
                </div>
            </template>

            <template #header.id>
                <v-checkbox :indeterminate="selectPart.length > 0 && selectPart.length < part.length"
                    @click.native="checkAll" v-model="selectAll"></v-checkbox>
            </template>

            <template #item.id="{ item }">
                <v-checkbox v-model="selectPart" :value="item"></v-checkbox>
            </template>
        </v-data-table>
    </div>
</template>