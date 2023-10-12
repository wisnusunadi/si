<script>
import moment from 'moment'
import pagination from '../../components/pagination.vue'
import TableKalender from '../../components/TableKalender.vue'
import modalRework from '../modalRework/index.vue'
import axios from 'axios'
export default {
    components: {
        pagination,
        TableKalender,
        modalRework
    },
    data() {
        return {
            search: '',
            dataTable: [],
            renderPaginate: [],
            monthYears: moment().format('MMMM YYYY'),
            showModal: false,
            dataProduk: null
        }
    },
    methods: {
        updateFilteredDalamProses(filteredDalamProses) {
            this.renderPaginate = filteredDalamProses
        },
        edit(id) {
            this.dataProduk = JSON.parse(JSON.stringify(this.dataTable.find((data) => data.id === id)))
            this.showModal = true
        },
        hapus(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00d1b2',
                cancelButtonColor: '#ff3860',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.dataTable = this.dataTable.filter((data) => data.id !== id)
                    this.$swal(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    )
                }
            })
        },
        async simpan(data) {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil disimpan!', 'success')
                this.showModal = false
                this.getData()
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal disimpan!', 'error')
            }

            try {
                await axios.post('/api/ppic/jadwal_rework/pelaksanaan', data).then(success).catch(error)
            } catch (error) {
                console.log(error)
            }
        },
        simpanedit(data) {
            this.showModal = false
            this.$swal({
                title: 'Berhasil!',
                text: "Data berhasil disimpan!",
                icon: 'success',
                confirmButtonColor: '#00d1b2',
                confirmButtonText: 'OK'
            })
        },
        tambah() {
            this.dataProduk = {
                produk_id: '',
                jumlah: '',
                tanggal_mulai: '',
                tanggal_selesai: '',
            }
            this.showModal = true
        },
        async getData() {
            try {
                this.$store.commit('setIsLoading', true)
                const { data } = await axios.get('/api/ppic/jadwal_rework/pelaksanaan')
                this.dataTable = data
                this.renderPaginate = data
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.commit('setIsLoading', false)
            }
        }
    },
    computed: {
        filteredData() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
    mounted() {
        this.getData()
    }
}
</script>
<template>
    <div>
        <modalRework v-if="showModal" :dataProduk="dataProduk" :showModal="showModal" @closeModal="showModal = false"
            :maxDate="monthYears" @tambah="simpan" @edit="simpanedit" />
        <h1 class="title">Perencanaan Jadwal Perakitan Rework</h1>
        <div class="notification is-primary">
            Penyusunan jadwal perakitan rework
        </div>
        <div class="card">
            <div class="card-content">
                <h1 class="subtitle">
                    {{ monthYears }}
                </h1>

                <div class="columns">
                    <div class="column">
                        <button class="button is-success" @click="tambah">
                            Tambah &nbsp;<i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="column is-flex-shrink-1">
                        <input type="text" class="input" v-model="search" placeholder="Cari">
                    </div>
                </div>

                <TableKalender :dataTable="renderPaginate" :monthYears="monthYears" @edit="edit" @hapus="hapus" />

                <pagination :filteredDalamProses="filteredData" @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>