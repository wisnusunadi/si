<script>
import moment from 'moment'
import pagination from '../../components/pagination.vue'
import TableKalender from '../../components/TableKalender.vue'
export default {
    components: {
        pagination,
        TableKalender,
    },
    data() {
        return {
            search: '',
            dataTable: [
                {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-03',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }
                , {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }
                , {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },{
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },{
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },{
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },{
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },{
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }, {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-11-10',
                }
            ],
            renderPaginate: [],
            monthYears: moment().add(1, 'month').format('MMMM YYYY'),
        }
    },
    methods: {
        updateFilteredDalamProses(filteredDalamProses) {
            this.renderPaginate = filteredDalamProses
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
    }
}
</script>
<template>
    <div>
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
                        <button class="button is-success">
                            Tambah &nbsp;<i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="column is-flex-shrink-1">
                        <input type="text" class="input" v-model="search" placeholder="Cari">
                    </div>
                </div>

                <TableKalender :dataTable="renderPaginate" :monthYears="monthYears" />

                <pagination :filteredDalamProses="filteredData" @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>