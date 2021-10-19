<template>
    <div class="content">
        <div class="row d-flex justify-content-center">
            <div class="col-11">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-bottom: 5px">
                            <div class="col-12">
                                <span class="float-right">
                                    <router-link :to="{ name: 'create' }"
                                        ><button class="btn btn-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button></router-link
                                    >
                                </span>
                                <span
                                    class="float-right"
                                    style="margin-right: 5px"
                                >
                                    <button
                                        class="
                                            btn btn-outline-info
                                            dropdown-toggle
                                        "
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div
                                        class="
                                            dropdown-menu dropdown-menu-right
                                        "
                                    >
                                        <form action="" class="px-4 py-3">
                                            <div class="dropdown-header">
                                                Kelompok Produk
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownkelompokproduk"
                                                        value="alat_kesehatan"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownkelompokproduk"
                                                    >
                                                        Alat Kesehatan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownkelompokproduk"
                                                        value="sarana_kesehatan"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownkelompokproduk"
                                                    >
                                                        Sarana Kesehatan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownkelompokproduk"
                                                        value="aksesoris"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownkelompokproduk"
                                                    >
                                                        Aksesoris
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownkelompokproduk"
                                                        value="lain"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownkelompokproduk"
                                                    >
                                                        Lain - lain
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="dropdown-header">
                                                Stok
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownstok"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownstok"
                                                    >
                                                        Tersedia
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownstok"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownstok"
                                                    >
                                                        Hampir Habis
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="dropdownstok"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="dropdownstok"
                                                    >
                                                        Habis
                                                    </label>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary">
                                                Cari
                                            </button>
                                        </form>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead style="text-align: center">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Kelompok Produk</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div
                                                    class="
                                                        d-flex
                                                        align-items-center
                                                    "
                                                >
                                                    <div class="symbol">
                                                        <img
                                                            src="https://picsum.photos/200/200"
                                                            alt=""
                                                            class="
                                                                rounded-circle
                                                            "
                                                            style="
                                                                width: 30px;
                                                                height: 30px;
                                                            "
                                                        />
                                                    </div>
                                                    <div
                                                        class="
                                                            d-flex
                                                            justify-content-start
                                                            flex-column
                                                        "
                                                        style="
                                                            margin-left: 10px;
                                                        "
                                                    >
                                                        Anesthesia Nebulizer
                                                        Promist 1
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>
                                                <span>Rp.</span
                                                ><span class="float-right"
                                                    >1000000</span
                                                >
                                            </td>
                                            <td>
                                                <span class="float-right"
                                                    >100</span
                                                >
                                            </td>
                                            <td>
                                                <div
                                                    class="dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    id="dropdownMenuButton"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                >
                                                    <i
                                                        class="
                                                            fas
                                                            fa-ellipsis-v
                                                        "
                                                    ></i>
                                                </div>
                                                <div
                                                    class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton"
                                                >
                                                    <a
                                                        data-toggle="modal"
                                                        data-target="#modaldetail"
                                                        ><button
                                                            class="
                                                                dropdown-item
                                                            "
                                                            type="button"
                                                        >
                                                            <i
                                                                class="
                                                                    fas
                                                                    fa-search
                                                                "
                                                            ></i>
                                                            Detail
                                                        </button></a
                                                    >
                                                    <a
                                                        data-toggle="modal"
                                                        data-target="#modaledit"
                                                        ><button
                                                            class="
                                                                dropdown-item
                                                            "
                                                            type="button"
                                                        >
                                                            <i
                                                                class="
                                                                    fas
                                                                    fa-pencil-alt
                                                                "
                                                            ></i>
                                                            Edit
                                                        </button></a
                                                    >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div
                                                    class="
                                                        d-flex
                                                        align-items-center
                                                    "
                                                >
                                                    <div class="symbol">
                                                        <img
                                                            src="https://picsum.photos/200/200"
                                                            alt=""
                                                            class="
                                                                rounded-circle
                                                            "
                                                            style="
                                                                width: 30px;
                                                                height: 30px;
                                                            "
                                                        />
                                                    </div>
                                                    <div
                                                        class="
                                                            d-flex
                                                            justify-content-start
                                                            flex-column
                                                        "
                                                        style="
                                                            margin-left: 10px;
                                                        "
                                                    >
                                                        Anesthesia Nebulizer
                                                        Promist 1
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>
                                                <span>Rp.</span
                                                ><span class="float-right"
                                                    >1000000</span
                                                >
                                            </td>
                                            <td>
                                                <span class="float-right"
                                                    >100</span
                                                >
                                            </td>
                                            <td>
                                                <div
                                                    class="dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    id="dropdownMenuButton"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                >
                                                    <i
                                                        class="
                                                            fas
                                                            fa-ellipsis-v
                                                        "
                                                    ></i>
                                                </div>
                                                <div
                                                    class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton"
                                                >
                                                    <a
                                                        data-toggle="modal"
                                                        data-target="#modaldetail"
                                                        ><button
                                                            class="
                                                                dropdown-item
                                                            "
                                                            type="button"
                                                        >
                                                            <i
                                                                class="
                                                                    fas
                                                                    fa-search
                                                                "
                                                            ></i>
                                                            Detail
                                                        </button></a
                                                    >
                                                    <a
                                                        data-toggle="modal"
                                                        data-target="#modaledit"
                                                        ><button
                                                            class="
                                                                dropdown-item
                                                            "
                                                            type="button"
                                                        >
                                                            <i
                                                                class="
                                                                    fas
                                                                    fa-pencil-alt
                                                                "
                                                            ></i>
                                                            Edit
                                                        </button></a
                                                    >
                                                </div>
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

        <div
            class="modal fade"
            id="modaldetail"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modaldetail"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title">Detail</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span style="font-size: 24px"
                                                    ><b>Info</b></span
                                                ><span
                                                    class="
                                                        float-right
                                                        green-text
                                                        col-form-label
                                                    "
                                                    ><b>Tersedia</b></span
                                                >
                                            </li>
                                            <li class="list-group-item">
                                                <a>Nama Produk</a
                                                ><span id="nama_produk"></span
                                                ><b class="float-right"
                                                    >FOX-BABY + Case</b
                                                >
                                            </li>
                                            <li class="list-group-item">
                                                <a>Harga</a
                                                ><span
                                                    id="kelompok_produk"
                                                ></span
                                                ><b class="float-right"
                                                    >Rp. 1.000.000,00</b
                                                >
                                            </li>
                                            <li class="list-group-item">
                                                <a>Stok</a
                                                ><span id="stok"></span
                                                ><b class="float-right">100</b>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <h5>Detail Produk</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Kelompok</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>FOX-BABY</td>
                                                    <td>Alat Kesehatan</td>
                                                    <td>1</td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Case</td>
                                                    <td>Aksesori</td>
                                                    <td>1</td>
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
        </div>

        <div
            class="modal fade"
            id="modaledit"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modaledit"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning"><h4>Edit</h4></div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row d-flex justify-content-center">
                                <div class="col-11">
                                    <h5>Info Umum Paket</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <label
                                                            for="nama_produk"
                                                            class="col-4 col-form-label"
                                                            style="text-align: right;"
                                                            >Nama Paket</label
                                                        >
                                                        <div class="col-6">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                placeholder="Masukkan Nama Paket"
                                                                v-model="
                                                                    nama_paket
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            for="nama_produk"
                                                            class=" col-4 col-form-label"
                                                            style="text-align: right;"
                                                            >Harga</label
                                                        >
                                                        <div
                                                            class="input-group col-5"
                                                        >
                                                            <div
                                                                class="input-group-prepend"
                                                            >
                                                                <span
                                                                    class="
                                                                        input-group-text
                                                                    "
                                                                    >Rp</span
                                                                >
                                                            </div>
                                                            <input
                                                                type="text"
                                                                class="
                                                                    form-control
                                                                "
                                                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                                                value=""
                                                                data-type="currency"
                                                                placeholder="Masukkan Harga"
                                                                v-model="
                                                                    hargaedit
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-11">
                                    <h5>Detail Produk Paket</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <table
                                                        class="table"
                                                        style="
                                                            text-align: center;
                                                        "
                                                        id="edittable"
                                                    >
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5">
                                                                    <button
                                                                        type="button"
                                                                        class="
                                                                            btn
                                                                            btn-primary
                                                                            float-right
                                                                        "
                                                                        @click="
                                                                            addRow()
                                                                        "
                                                                    >
                                                                        <i
                                                                            class="
                                                                                fas
                                                                                fa-plus
                                                                            "
                                                                        ></i>
                                                                        Produk
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama</th>
                                                                <th>
                                                                    Kelompok
                                                                </th>
                                                                <th>Jumlah</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="(row,
                                                                index) in rowsedit"
                                                            >
                                                                <td>
                                                                    {{
                                                                        index +
                                                                            1
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="
                                                                            form-group
                                                                        "
                                                                    >
                                                                        <select
                                                                            class="
                                                                                form-control
                                                                                custom-select
                                                                            "
                                                                            v-model="
                                                                                row.produk_id
                                                                            "
                                                                        >
                                                                            <option
                                                                                value=""
                                                                            >
                                                                                Anesthesia
                                                                                Medical
                                                                                Nebulizer
                                                                                Promist
                                                                                1
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        row.kelompok_produk
                                                                    }}Alat
                                                                    Kesehatan
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="
                                                                            form-group
                                                                            d-flex
                                                                            justify-content-center
                                                                        "
                                                                    >
                                                                        <input
                                                                            type="number"
                                                                            class="
                                                                                form-control
                                                                            "
                                                                            v-model="
                                                                                row.jumlah
                                                                            "
                                                                            style="
                                                                                width: 50%;
                                                                            "
                                                                        />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a
                                                                        @click="
                                                                            removeRow(
                                                                                index
                                                                            )
                                                                        "
                                                                        ><i
                                                                            class="
                                                                                fas
                                                                                fa-minus
                                                                            "
                                                                            style="
                                                                                color: red;
                                                                            "
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <span class="float-left"
                            ><button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Batal
                            </button></span
                        >
                        <span class="float-right"
                            ><button type="button" class="btn btn-warning">
                                Simpan
                            </button></span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            hargaedit: "",
            rowsedit: [
                {
                    produk_id: "",
                    kelompok_produk: "",
                    jumlah: "",
                    produk_iderror: false,
                    kelompok_produkerror: false,
                    jumlaherror: false
                }
            ]
        };
    },
    watch: {
        hargaedit: function(newValue) {
            const result = newValue
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            this.harga = result;
        }
    }
};
</script>

<style>
td:nth-child(1),
td:nth-child(6) {
    text-align: center;
}
td:nth-child(1),
td:nth-child(4),
td:nth-child(5),
td:nth-child(6) {
    white-space: nowrap;
}
.list-group li {
    border: 0;
    font-size: 14px;
}
</style>
