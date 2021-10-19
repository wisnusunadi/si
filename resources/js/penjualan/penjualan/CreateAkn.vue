<template>
    <div class="content">
        <form @submit.prevent="handleSubmit">
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <h4>Info Customer</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Nama Customer</label
                                    >
                                    <div class="col-5">
                                        <select
                                            class="form-control col-form-label"
                                            v-model="customer_id"
                                            @change="changeCust($event)"
                                        >
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Alamat</label
                                    >
                                    <div class="col-7">
                                        <input
                                            type="text"
                                            class="form-control col-form-label"
                                            v-model="alamat"
                                            readonly
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Telepon</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="text"
                                            class="form-control col-form-label"
                                            v-model="telepon"
                                            readonly
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Instansi</label
                                    >
                                    <div class="col-7">
                                        <input
                                            type="text"
                                            class="form-control col-form-label"
                                            v-model="instansi"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <h4>Info AKN</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Tanggal Pemesanan</label
                                    >
                                    <div class="col-4">
                                        <input
                                            type="date"
                                            class="form-control col-form-label"
                                            v-model="tanggal_pemesanan"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >No Paket</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="text"
                                            class="form-control col-form-label"
                                            v-model="no_paket"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Batas Kontrak</label
                                    >
                                    <div class="col-4">
                                        <input
                                            type="date"
                                            class="form-control col-form-label"
                                            v-model="batas_kontrak"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Deskripsi</label
                                    >
                                    <div class="col-5">
                                        <textarea
                                            class="form-control col-form-label"
                                            v-model="deskripsi"
                                            v-bind:class="{
                                                'is-invalid': deskripsierror
                                            }"
                                        ></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >Keterangan</label
                                    >
                                    <div class="col-5">
                                        <textarea
                                            class="form-control col-form-label"
                                            v-model="keterangan"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <h4>Data Produk</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table
                                        class="table"
                                        style="text-align: center"
                                    >
                                        <thead>
                                            <tr>
                                                <th colspan="5">
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary float-right"
                                                        @click="addRow()"
                                                    >
                                                        <i
                                                            class="fas fa-plus"
                                                        ></i>
                                                        Produk
                                                    </button>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Paket</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in rows">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select
                                                            class="form-control custom-select"
                                                            v-model="
                                                                row.produk_id
                                                            "
                                                        >
                                                            <option value="">
                                                                Anesthesia
                                                                Medical
                                                                Nebulizer
                                                                Promist 1
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="form-group d-flex justify-content-center"
                                                    >
                                                        <input
                                                            type="number"
                                                            class="form-control"
                                                            v-model="row.jumlah"
                                                            style="width: 50%"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <a @click="removeRow(index)"
                                                        ><i
                                                            class="fas fa-minus"
                                                            style="color: red"
                                                        ></i
                                                    ></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="row">
                    <div class="col-10">
                        <span class="float-left">
                            <router-link :to="{ name: 'show' }"
                                ><button class="btn btn-danger">
                                    Batal
                                </button></router-link
                            >
                        </span>
                        <span class="float-right"
                            ><button type="submit" class="btn btn-info">
                                Tambah
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            deskripsi: "",
            deskripsierror: false,
            customer_id: "",
            keterangan: "",
            alamat: "",
            telepon: "",
            instansi: "",
            rows: [
                {
                    produk_id: "",
                    jumlah: "",
                    produk_iderror: false,
                    jumlaherror: false
                }
            ]
        };
    },
    methods: {
        addRow: function() {
            this.rows.push({
                produk_id: "",
                jumlah: ""
            });
        },
        removeRow: function(index) {
            //console.log(row);
            this.rows.splice(index, 1);
        },
        handleSubmit: function() {
            if (
                this.instansi != "" ||
                this.deskripsi != "" ||
                this.keterangan != ""
            ) {
                console.log("success");
            } else {
                this.deskripsierror = true;
            }
        },
        changeCust: function(e) {}
    },
    watch: {
        harga: function(newValue) {
            const result = newValue
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            this.harga = result;
        }
    }
};
</script>
