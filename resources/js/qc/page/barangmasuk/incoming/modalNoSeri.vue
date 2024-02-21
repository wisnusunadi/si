<script>
export default {
    props: ['produk'],
    data() {
        return {
            noseri: [
                {
                    id: 1,
                    noseri: 'TD123456'
                },
                {
                    id: 2,
                    noseri: 'TD123457'
                },
                {
                    id: 3,
                    noseri: 'TD123458',
                    tgl_uji: '2024-02-02',
                    waktu_uji: '12:00',
                    hasil_uji: 'ok',
                },
                {
                    id: 4,
                    noseri: 'TD123459',
                    tgl_uji: '2024-02-02',
                    waktu_uji: '13:00',
                    hasil_uji: 'nok',
                },
                {
                    id: 5,
                    noseri: 'TD123460'
                },
            ],
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                    align: 'text-left'
                },
                {
                    text: 'id',
                    value: 'id',
                    align: 'text-left'
                }
            ],
            search: '',
            noSeriSelected: [],
            checkAll: false
        }
    },
    methods: {
        closeModal() {
            $('.modalNoSeri').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        badgeNoSeri(item) {
            switch (item) {
                case 'ok':
                    return {
                        text: 'Lolos',
                        badge: 'badge badge-success'
                    }

                default:
                    return {
                        text: 'Tidak Lolos',
                        badge: 'badge badge-danger'
                    }
            }
        },
        selectNoSeri(item) {
            if (item.hasil_uji) {
                return
            }
            if (this.noSeriSelected.find(i => i.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter(i => i.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalNoSeri" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">No Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight"></div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="noseri" :search="search">
                        <template #header.id>
                            <input type="checkbox" :checked="checkAll" />
                        </template>
                        <template #item.id="{ item }">
                            <input type="checkbox" v-if="!item?.hasil_uji"
                                :checked="noSeriSelected && noSeriSelected.find(i => i.id === item.id)" />
                            <div v-else>
                                <span class="badge badge-info">{{ item.tgl_uji }} {{ item.waktu_uji }}</span><br>
                                <span :class="badgeNoSeri(item.hasil_uji).badge">{{ badgeNoSeri(item.hasil_uji).text
                                }}</span>
                            </div>
                        </template>
                    </data-table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
