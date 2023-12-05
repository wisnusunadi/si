<script>
import moment from 'moment'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'
export default {
    components: {
        DataTable,
    },
    props: ['produk'],
    data() {
        return {
            dataRiwayat: [],
            headers: [{
                text: 'No Seri',
                value: 'noseri',
                align: 'text-left'
            }],
            search: '',
        }
    },
    methods: {
        async getRiwayat() {
            try {
                const date = moment(this.produk.waktu_tf).format('YYYY-MM-DD')
                const dateTime = `${date} ${this.produk.waktu}`
                const { data } = await axios.get(`/api/prd/historySeri/${this.produk.produk_id}/${dateTime}`)
                this.dataRiwayat = data
            } catch (error) {
                console.log(error)
            }
        },
        close() {
            $('.modalRiwayat').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
    },
    mounted() {
        this.getRiwayat()
    },
}
</script>
<template>
    <div class="modal fade modalRiwayat" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Riwayat Produk</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
      <div class="p-2 bd-highlight"><input type="text" v-model="search" class="form-control" placeholder="Cari..."></div>
    </div>
                    <DataTable :headers="headers" :items="dataRiwayat" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>