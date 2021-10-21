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
                                        <v-select
                                            :options="optionProduk"
                                            label="produk_id"
                                            v-model="produk_id"
                                        />
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
                                        >Jenis Penjualan</label
                                    >
                                    <div class="col-5 col-form-label">
                                        <div
                                            class="form-check form-check-inline"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                v-model="jenis_penjualan"
                                                name="jenis_penjualan"
                                                id="jenis_penjualan1"
                                                value="ekatalog"
                                                @click="clickEkatalog"
                                            />
                                            <label
                                                class="form-check-label"
                                                for="jenis_penjualan1"
                                                >E-Catalogue</label
                                            >
                                        </div>
                                        <div
                                            class="form-check form-check-inline"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                v-model="jenis_penjualan"
                                                name="jenis_penjualan"
                                                id="jenis_penjualan2"
                                                value="spa"
                                                @click="clickSpa"
                                            />
                                            <label
                                                class="form-check-label"
                                                for="jenis_penjualan2"
                                                >SPA</label
                                            >
                                        </div>
                                        <div
                                            class="form-check form-check-inline"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                v-model="jenis_penjualan"
                                                name="jenis_penjualan"
                                                id="jenis_penjualan3"
                                                value="spb"
                                                @click="clickSpb"
                                            />
                                            <label
                                                class="form-check-label"
                                                for="jenis_penjualan3"
                                                >SPB</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="row d-flex justify-content-center"
                v-bind:class="{ hide: akn }"
            >
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
                                            v-bind:class="{
                                                'is-invalid': tanggal_pemesananer
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.tanggal_pemesanan"
                                        >
                                            {{ msg.tanggal_pemesanan }}
                                        </div>
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
                                            v-bind:class="{
                                                'is-invalid': instansier
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.instansi"
                                        >
                                            {{ msg.instansi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for=""
                                        class="col-form-label col-5"
                                        style="text-align: right"
                                        >No Paket</label
                                    >
                                    <div class="col-5 input-group">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text"
                                                id="ak1"
                                                >AK1</span
                                            >
                                        </div>
                                        <input
                                            type="text"
                                            class="form-control col-form-label"
                                            v-model="no_paket"
                                            id="ak1"
                                            v-bind:class="{
                                                'is-invalid': no_paketer
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.no_paket"
                                        >
                                            {{ msg.no_paket }}
                                        </div>
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
                                            v-bind:class="{
                                                'is-invalid': batas_kontraker
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.batas_kontrak"
                                        >
                                            {{ msg.batas_kontrak }}
                                        </div>
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
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.deskripsi"
                                        >
                                            {{ msg.deskripsi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="keterangan"
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
            <div
                class="row d-flex justify-content-center"
                v-bind:class="{ hide: nonakn }"
            >
                <div class="col-10">
                    <h4>Info Penjualan</h4>
                    <div class="card">
                        <div class="card-body">
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
                                        v-model="nonakntanggal_pemesanan"
                                        v-bind:class="{
                                            'is-invalid': nonakntanggal_pemesananer
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback"
                                        v-if="msg.nonakntanggal_pemesanan"
                                    >
                                        {{ msg.nonakntanggal_pemesanan }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="keterangan"
                                    class="col-form-label col-5"
                                    style="text-align: right"
                                    >Keterangan</label
                                >
                                <div class="col-5">
                                    <textarea
                                        class="form-control col-form-label"
                                        v-model="nonaknketerangan"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="row d-flex justify-content-center"
                v-bind:class="{ hide: dataproduk }"
            >
                <div class="col-10">
                    <h4>Data Produk</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table
                                            class="table"
                                            style="text-align: center;"
                                        >
                                            <thead>
                                                <tr>
                                                    <th colspan="7">
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary float-right"
                                                            @click="
                                                                addRowProduk()
                                                            "
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
                                                    <th>Variasi</th>
                                                    <th>Jumlah</th>
                                                    <th>Ketersediaan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in rowsProduk"
                                                >
                                                    <td>{{ index + 1 }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <v-select
                                                                :options="
                                                                    optionProduk
                                                                "
                                                                label="produk_id"
                                                                v-model="
                                                                    row.produk_id
                                                                "
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <v-select
                                                                :options="
                                                                    optionVariasi
                                                                "
                                                                label="produk_id"
                                                                v-model="
                                                                    row.variasi
                                                                "
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="form-group d-flex justify-content-center"
                                                        >
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                v-model="
                                                                    row.jumlah
                                                                "
                                                                style="width: 50%"
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ row.ketersediaan }}
                                                    </td>
                                                    <td>
                                                        <a
                                                            @click="
                                                                removeRowProduk(
                                                                    index
                                                                )
                                                            "
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
            </div>
            <div
                class="row d-flex justify-content-center"
                v-bind:class="{ hide: datapart }"
            >
                <div class="col-10">
                    <h4>Data Part</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table
                                            class="table"
                                            style="text-align: center;"
                                        >
                                            <thead>
                                                <tr>
                                                    <th colspan="7">
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary float-right"
                                                            @click="
                                                                addRowPart()
                                                            "
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
                                                    <th>Nama Part</th>
                                                    <th>Jumlah</th>
                                                    <th>Ketersediaan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in rowsPart"
                                                >
                                                    <td>{{ index + 1 }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <v-select
                                                                :options="
                                                                    optionPart
                                                                "
                                                                label="part_id"
                                                                v-model="
                                                                    row.part_id
                                                                "
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="form-group d-flex justify-content-center"
                                                        >
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                v-model="
                                                                    row.jumlah
                                                                "
                                                                style="width: 50%"
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ row.ketersediaan }}
                                                    </td>
                                                    <td>
                                                        <a
                                                            @click="
                                                                removeRowPart(
                                                                    index
                                                                )
                                                            "
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
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <span>
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
        </form>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            msg: [],
            customer_id: "",
            jenis_penjualan: "",
            tanggal_pemesanan: "",
            batas_kontrak: "",

            keterangan: "",
            alamat: "",
            telepon: "",
            instansi: "",
            no_paket: "",
            deskripsi: "",

            noakntanggal_pemesanan: "",
            nonaknketerangan: "",

            optionProduk: [],
            optionVariasi: [],
            rowsProduk: [
                {
                    produk_id: "",
                    variasi: "",
                    jumlah: "",
                    ketersediaan: "",

                    produk_iderror: false,
                    jumlaherror: false
                }
            ],
            rowsPart: [
                {
                    part_id: "",
                    jumlah: "",
                    ketersediaan: ""
                }
            ],

            dataproduk: true,
            datapart: true,
            nonakn: true,
            akn: true
        };
    },
    methods: {
        addRowProduk: function() {
            this.rowsProduk.push({
                produk_id: "",
                variasi: "",
                jumlah: "",
                ketersediaan: ""
            });
        },
        removeRowProduk: function(index) {
            //console.log(row);
            this.rowsProduk.splice(index, 1);
        },
        addRowPart: function() {
            this.rowsPart.push({
                produk_id: "",
                variasi: "",
                jumlah: "",
                ketersediaan: ""
            });
        },
        removeRowPart: function(index) {
            //console.log(row);
            this.rowsPart.splice(index, 1);
        },
        handleSubmit: function() {
            console.log(this.jenis_penjualan);
        },
        clickEkatalog: function() {
            this.dataproduk = false;
            this.datapart = true;
            this.akn = false;
            this.nonakn = true;
            console.log("ekatalog");
        },
        clickSpa: function() {
            this.dataproduk = false;
            this.datapart = true;
            this.akn = true;
            this.nonakn = false;
            console.log("spa");
        },
        clickSpb: function() {
            this.dataproduk = true;
            this.datapart = false;
            this.akn = true;
            this.nonakn = false;
            console.log("spb");
        },
        changeCust: function(e) {},
        getCustomer: function(value) {}
    },
    watch: {
        customer_id: function() {
            if (this.customer_id.value != "") {
                this.customer_ider = false;
                const value = getCustomer(this.customer_id);
                this.alamat = value.alamat;
                this.telepon = value.telepon;
            } else if (this.customer_id == "") {
                this.msg["customer_id"] = "Silahkan Pilih Customer";
                this.customerer = true;
            }
        },
        tanggal_pemesanan: function() {
            if (this.tanggal_pemesanan != "") {
                this.tanggal_pemesananer = false;
            } else if (this.tanggal_pemesanan == "") {
                this.msg["tanggal_pemesanan"] = "Isi Tanggal Pemesanan";
                this.tanggal_pemesananer = true;
            }
        },
        batas_kontrak: function() {
            if (this.batas_kontrak != "") {
                this.batas_kontraker = false;
            } else if (this.batas_kontrak == "") {
                this.msg["batas_kontrak"] = "Batas Kontrak Harus diisi";
                this.batas_kontraker = true;
            }
        },
        instansi: function() {
            if (this.instansi != "") {
                this.instansier = false;
            } else if (this.instansi == "") {
                this.msg["instansi"] = "Instansi Harus diisi";
                this.instansier = true;
            }
        },
        deskripsi: function() {
            if (this.deskripsi != "") {
                this.deskripsier = false;
            } else if (this.deskripsi == "") {
                this.msg["deskripsi"] = "Deskripsi harus diisi";
                this.deskripsier = true;
            }
        },
        no_paket: function() {
            if (this.no_paket != "") {
                this.no_paketer = false;
            } else if (this.no_paket == "") {
                this.msg["no_paket"] = "No Paket harus diisi";
                this.no_paketer = true;
            }
        },

        nonakntanggal_pemesanan: function() {
            if (this.nonakntanggal_pemesanan != "") {
                this.nonakntanggal_pemesananer = false;
            } else if (this.nonakntanggal_pemesanan == "") {
                this.msg["nonakntanggal_pemesanan"] = "Isi Tanggal Pemesanan";
                this.nonakntanggal_pemesananer = true;
            }
        }
    }
};
</script>
<style>
.hide {
    display: none !important;
}
</style>
