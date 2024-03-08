<script>
import axios from 'axios'
export default {
    props: ['detailSelected'],
    data() {
        return {
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            items: [],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalNoseri').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/gbj/modal_data_seri_tf/${this.detailSelected.id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('lokal_token')}`
                    }
                })
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>

<template>
    <div class="modal fade modalNoseri" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="items" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>