<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        DataTable,
    },
    props: ['selectSeri'],
    data() {
        return {
            noseri: [],
            isDisable: false,
            errorValue: '',
            isError: false,
            hasilGenerate: null,
            noseriGeneratePackingList: [],
            headersDokumen: [
                {text: 'No.', value: 'no', align: 'text-left'},
                {text: 'No. Seri', value: 'noseri', align: 'text-left'},
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalGenerate').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
                this.$emit('refresh')
            });
        },
        generateNoSeri() {
            if (!this.selectSeri?.id) {
                for (let i = 0; i < 3; i++) {
                    this.noseri.push({
                        seri: '',
                    });
                }
            } else {
                this.noseri = this.selectSeri.seri.map((data) => {
                    return {
                        seri: data.noseri,
                    }
                })
            }
        },
        autoTab(e, idx) {
            if (this.noseri[idx].error) {
                delete this.noseri[idx].error
            }

            if (!this.noseri.find((data) => data.error)) {
                this.isError = false
            }

            if (e.target.value) {
                const cek = this.noseri.filter((data) => {
                    return data.seri.trim() === '';
                })

                if (!this.isError && cek.length === 0 && !this.selectSeri?.id) {
                    this.simpanSeri();
                }

                if (idx < this.noseri.length - 1) {
                    this.$refs.noseri[idx + 1].focus()
                }
            }
        },
        simpanSeri() {
            this.noseri = this.noseri.map((data) => {
                delete data.error
                return data
            })

            const cek = this.noseri.filter((data) => {
                return data.seri.trim() === '';
            });


            const noSeriUnique = this.noseri.filter((data, index) => {
                return this.noseri.findIndex((data2) => data2.seri === data.seri) === index;
            })

            if (noSeriUnique.length !== this.noseri.length) {
                this.isError = true
                this.errorValue = 'tidak boleh sama'
                this.noseri = this.noseri.map((data) => {
                    if (this.noseri.findIndex((data2) => data2.seri === data.seri) !== this.noseri.lastIndexOf(data)) {
                        data.error = true
                    }
                    return data
                })
                this.$swal('Gagal', 'No. Seri tidak boleh sama', 'error')
                return
            }

            if (cek.length > 0) {
                this.$swal('Gagal', 'No. Seri tidak boleh kosong', 'error')
                return
            }

            if (!this.isDisable && cek.length === 0 && noSeriUnique.length === this.noseri.length) {
                this.isDisable = true


                this.hasilGenerate = 'PETI-1'
                this.noseriGeneratePackingList = this.noseri.map((data) => {
                    return {
                        noseri: data.seri,
                    }
                })
                this.$swal('Berhasil', 'No. Seri berhasil disimpan', 'success')
            }
        },
        updateSeri() {

        },
        mappingEdit() {
            if (this.selectSeri?.id) {
                this.hasilGenerate = this.selectSeri.no_peti
                this.noseriGeneratePackingList = this.selectSeri.seri
            }
        },  
        resetModal() {
            this.noseri = []
            this.generateNoSeri()
            this.isDisable = false
            this.isError = false;
            this.hasilGenerate = null;
            this.noseriGeneratePackingList = [];
            this.$nextTick(() => {
                setTimeout(() => {
                    this.$refs.noseri[0].focus();
                }, 200);
            });
        }
    },
    mounted() {
        this.generateNoSeri();
        this.mappingEdit();
        this.$nextTick(() => {
            setTimeout(() => {
                this.$refs.noseri[0].focus();
            }, 200);
        });
    },
}
</script>
<template>
    <div class="modal fade modalGenerate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Scan Produk Pemetian</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No. Seri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, idx) in noseri" :key="idx">
                                        <td>
                                            <input type="text" class="form-control" :class="data.error ? 'is-invalid' : ''"
                                                @keyup.enter="autoTab($event, idx)" ref="noseri" :disabled="isDisable"
                                                v-model="data.seri">
                                            <div class="invalid-feedback">
                                                Nomor Seri {{ errorValue }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col" v-if="hasilGenerate">
                            <div class="row">
                                <div class="col">
                                    <label for="">Nomor Peti</label>
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            <span id="so">{{ hasilGenerate }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-grow-1 bd-highlight">
                                            <button class="btn btn-sm btn-outline-primary">
                                                Cetak No. Packing List <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                        <div class="p-2 bd-highlight">
                                            <!-- bentuk modal untuk view nya -->
                                            <button class="btn btn-sm btn-outline-info">
                                                View No. Packing List <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <label for="">Dokumen Packing List</label>
                                    <DataTable :headers="headersDokumen" :items="noseriGeneratePackingList">
                                        <template #item.no = "{item, index}">
                                            <div>
                                                {{ index + 1 }}
                                            </div>
                                        </template>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex bd-highlight mb-3 mx-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <button type="button" class="btn btn-success">Simpan</button>
                    </div>
                    <div class="p-2 bd-highlight ml-auto">
                        <button type="button" class="btn btn-primary" @click="resetModal" v-if="!this.selectSeri?.id">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>