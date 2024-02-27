<script>
import axios from 'axios';
import modal from './modal.vue';
import Header from "../../../components/header.vue";
export default {
    components: {
        modal,
        Header,
    },
    data() {
        return {
            title: "Metode Uji Produk",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Metode Uji Produk",
                    link: "/master/alat",
                },
            ],
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Alat',
                    value: 'alat',
                }
            ],
            produk: [],
            search: '',
            produkSelected: [],
            checkAll: false,
            showModal: false
        }
    },
    created() {
        this.getProduk();
    },
    methods: {
        async getProduk() {
            try {
                this.$store.dispatch('setLoading', true)
                const { produk: produk } = await axios.get('/api/produkLab').then(res => res.data)
                this.produk = produk.map(item => {
                    return {
                        ...item,
                        alat: item.kode_lab.nama,
                        alat_selected: {
                            label: item.kode_lab.nama,
                            value: item.kode_lab.id
                        }
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        checkedAll() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.produkSelected = JSON.parse(JSON.stringify(this.produk))
            } else {
                this.produkSelected = []
            }
        },
        checkedItem(item) {
            const index = this.produkSelected.findIndex(produk => produk.id === item.id)
            if (index === -1) {
                this.produkSelected.push(item)
            } else {
                this.produkSelected.splice(index, 1)
            }
        },
        openModal() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        }
    },
    watch: {
        produkSelected: {
            handler: function (val) {
                if (val.length === this.produk.length) {
                    this.checkAll = true
                } else {
                    this.checkAll = false
                }
            },
            deep: true
        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />

        <div class="card">
            <modal v-if="showModal" :produk="produkSelected" @closeModal="showModal = false" @refresh="getProduk" />
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModal">Tambah / Edit Alat Produk</button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="produk" :search="search">
                    <template #header.id>
                        <input type="checkbox" @click="checkedAll" :checked="checkAll">
                    </template>
                    <template #item.id="{ item }">
                        <input type="checkbox" @click="checkedItem(item)"
                            :checked="produkSelected && produkSelected.find(produk => produk.id === item.id)">
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>