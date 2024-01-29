<script>
export default {
    props: ['detailRiwayat'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            items: [
                {
                    noseri: 'TD21241D01250'
                }
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalRiwayat').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        }
    }
}
</script>
<template>
    <div class="modal fade modalRiwayat" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Riwayat Penyerahan Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari nomor seri">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="items" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>