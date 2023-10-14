<script>
import status from '../../../components/status.vue';
import ModalTransfer from './modalTransfer';
export default {
    props: ['dataTable'],
    components: {
        status,
        ModalTransfer,
    },
    data() {
        return {
            showModalTransfer: false,
            idTransfer: null,
        }
    },
    methods: {
        kirim(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengirim data permintaan ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil dikirim',
                        icon: 'success',
                    })
                }
            })
        },
        closeModalSeri() {
            $('.modalSet').modal('hide');
            this.$nextTick(() => {
                this.showModal = false;

            });
        },
        statusReworks(belum, selesai) {
            if (selesai == 0) {
                return 'belum_dikerjakan'
            } else if (selesai > 0) {
                return 'sedang_dikerjakan'
            } else if (belum == 0) {
                return 'selesai'
            }
        },
        transferRework(id) {
            this.idTransfer = id
            this.showModalTransfer = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <ModalTransfer v-if="showModalTransfer" :id="idTransfer" @closeModal="showModalTransfer = false" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Tanggal Mulai</th>
                    <th rowspan="2">Tanggal Selesai</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>PRD-{{ data.urutan }}</td>
                    <td>{{ dateFormat(data.tgl_mulai) }}</td>
                    <td>{{ dateFormat(data.tgl_selesai) }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <status :status="statusReworks(data.belum, data.selesai)" />
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="transferRework(data.urutan)">
                            <i class="fas fa-paper-plane"></i>
                            Transfer
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>