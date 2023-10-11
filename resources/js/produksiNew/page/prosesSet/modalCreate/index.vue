<script>
export default {
    data() {
        return {
            noseri: [],
            hasilGenerate: null,
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal');
        },
        generateNoSeri() {
            for (let i = 0; i < 5; i++) {
                this.noseri.push({
                    seri: '',
                })
            }
        },
        autoTab(e, idx) {
            if (e.target.value) {
                if (idx < this.noseri.length - 1) {
                    this.$refs.noseri[idx + 1].focus();
                }
            }
        },
        generateSeri() {
            this.hasilGenerate = Math.floor(Math.random() * 10000000000000000);
        }
    },
    mounted() {
        this.generateNoSeri();
    },
    watch: {
        noseri: {
            handler(newVal) {
                const lastInput = newVal[newVal.length - 1].seri;
                if (lastInput && lastInput.trim() !== '') {
                    this.generateSeri();
                }
            },
            deep: true
        }
    }

}
</script>
<template>
    <div class="modal fade modalSet" data-backdrop="static" data-keyboard="false" id="modelId" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Scan Produk Rework</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <form @keypress.enter="generateSeri">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No. Seri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, idx) in noseri" :key="idx">
                                            <td>
                                                <input type="text" class="form-control" v-model="data.seri"
                                                    @input="autoTab($event, idx)" ref="noseri">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="col" v-if="hasilGenerate">
                            <div class="row">
                                <div class="col">
                                    <label for="">Nomor Seri</label>
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            <span id="so">{{ hasilGenerate }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-grow-1 bd-highlight">
                                            <button class="btn btn-sm btn-outline-primary">
                                                Cetak No. Seri <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                        <div class="p-2 bd-highlight">
                                            <!-- bentuk modal untuk view nya -->
                                            <button class="btn btn-sm btn-outline-info">
                                                View No. Seri <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- dokumen -->
                                    <label for="">Dokumen Packing List Hasil Generate</label>
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 flex-grow-1 bd-highlight">
                                            <button class="btn btn-sm btn-outline-primary">
                                                Cetak Packing List <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                        <div class="p-2 bd-highlight">
                                            <!-- bentuk pdf untuk view nya -->
                                            <button class="btn btn-sm btn-outline-info">
                                                View Packing List <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex bd-highlight mb-3 mx-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <button class="btn btn-success" @click="generateSeri">Generate</button>
                    </div>
                    <div class="p-2 bd-highlight ml-auto">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
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

.instansi {
    background-color: #36425E;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>