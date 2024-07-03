<script>
export default {
    data() {
        return {
            layout: [
                {
                    id: 7,
                    label: 'Blok B'
                },
                {
                    id: 5,
                    label: 'Blok D'
                },
                {
                    id: 6,
                    label: 'E10'
                },
                {
                    id: 4,
                    label: 'E14'
                },
                {
                    id: 1,
                    label: 'E7'
                }
            ],
            selectedLayout: null
        }
    },
    methods: {
        // close modal
        closeModal() {
            $('.modalLayout').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        // simpan
        simpan() {
            this.$emit('submit', this.selectedLayout)
            this.closeModal()
        }
    },
}
</script>
<template>
    <div class="modal fade modalLayout" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pilihan Layout</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Layout</label>
                        <v-select :options="layout" v-model="selectedLayout"></v-select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>