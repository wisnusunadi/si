<template>
    <div class="content">
        <form action="">
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <h5>Info Customer</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label
                                            for="nama_produk"
                                            class="col-4 col-form-label"
                                            style="text-align:right;"
                                            >Nama Customer</label
                                        >
                                        <div class="col-6">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Masukkan Nama Customer"
                                                v-model="nama_customer"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="nama_produk"
                                            class="col-4 col-form-label"
                                            style="text-align:right;"
                                            >Jenis</label
                                        >
                                        <div class="col-8">
                                            <div
                                                class="form-check form-check-inline  col-form-label"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    v-model="jenis"
                                                    name="jenis"
                                                    id="jenis1"
                                                    value="distributor"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="jenis1"
                                                    >Distributor</label
                                                >
                                            </div>
                                            <div
                                                class="form-check form-check-inline  col-form-label"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    v-model="jenis"
                                                    name="jenis"
                                                    id="jenis2"
                                                    value="perseorangan"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="jenis2"
                                                    >Perseorangan</label
                                                >
                                            </div>
                                            <div
                                                class="form-check form-check-inline col-form-label"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    v-model="jenis"
                                                    name="jenis"
                                                    id="jenis3"
                                                    value="supplier"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="jenis3"
                                                    >Supplier</label
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="alamat"
                                            class="col-4 col-form-label"
                                            style="text-align:right;"
                                            >Alamat</label
                                        >
                                        <div class="col-8">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Masukkan Alamat"
                                                v-model="alamat"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="telepon"
                                            class="col-4 col-form-label"
                                            style="text-align:right;"
                                            >No Telp</label
                                        >
                                        <div class="col-5">
                                            <input
                                                type="text"
                                                class="form-control"
                                                pattern="[7-9]{1}[0-9]{9}"
                                                value=""
                                                placeholder="Masukkan Telepon"
                                                v-model="telepon"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="telepon"
                                            class="col-4 col-form-label"
                                            style="text-align:right;"
                                            >Keterangan</label
                                        >
                                        <div class="col-5">
                                            <textarea
                                                class="form-control"
                                                name="keterangan"
                                                id="keterangan"
                                                v-model="keterangan"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <router-link :to="{ name: 'show' }"
                        ><span class="float-left"
                            ><button type="button" class="btn btn-danger">
                                Batal
                            </button></span
                        ></router-link
                    >
                    <span class="float-right"
                        ><button type="button" class="btn btn-info">
                            Tambah
                        </button></span
                    >
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            nama_customer: "",
            jenis: "",
            keterangan: "",
            telepon: "",
            alamat: ""
        };
    }
};
</script>
