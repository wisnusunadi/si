<script>
export default {
    data() {
        return {
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            items: [
                {
                    noseri: '1234567890'
                },
                {
                    noseri: '0987654321'
                }
            ],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetailNoSeri" data-backdrop="static" data-keyboard="false" tabindex="-1"
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