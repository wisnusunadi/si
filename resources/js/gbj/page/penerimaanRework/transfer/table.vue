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
        statusReworks(belum, selesai, jumlah) {
            if(selesai == jumlah) {
                return 'selesai'
            }

            if(selesai == 0){
                return 'belum_dikerjakan'
            }

            if(selesai != jumlah && belum !=0) {
                return 'sedang_dikerjakan'
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
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>PRD-{{ data.id }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="transferRework(data.id)">
                            <i class="fas fa-check"></i>
                            Terima
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
