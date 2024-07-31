<script>
import axios from "axios";
export default {
  props: ["selectProduct", "dialogCreate", "product"],
  data() {
    return {
      header: [
        { text: "Kelompok Produk", value: "kelompok_produk_id" },
        { text: "Merk", value: "merk" },
        { text: "Kode", value: "kode" },
        { text: "Nama", value: "nama" },
        { text: "Kategori", value: "kategori" },
        { text: "No AKD", value: "no_akd" },
        { text: "Status", value: "status" },
        { text: "Generate", value: "generate_seri" },
        { text: "Aksi", value: "aksi", sortable: false },
      ],
      selectAll: false,
      category: null,
      showDialog: false,
      kelompok: [
        { text: "Alat Kesehatan", value: 1 },
        { text: "Water Treatment", value: 2 },
        { text: "Aksesoris", value: 3 },
        { text: "Lain lain", value: 4 },
        { text: "Sparepart", value: 5 },
      ],
      valid: true,
      merk: ["MERK A", "MERK B", "MERK C", "MERK D"],
      rules: {
        required: (value) => !!value || "Required.",
        mustBeNumber: (value) => !isNaN(value) || "Must be a number",
        nameUnique: (id, value) => {
          return id
            ? true
            : !this.product.some((item) => item.nama === value) ||
                "Nama produk sudah ada";
        },
        kodeUnique: (id, value) => {
          return id
            ? true
            : !this.product.some((item) => item.kode === value) ||
                "Kode produk sudah ada";
        },
      },
      loading: false,
    };
  },
  methods: {
    async getCategory() {
      const { kategori } = await axios
        .get("/api/kategori")
        .then((res) => res.data);
      this.category = kategori.map((item) => ({
        text: item.nama,
        value: item.id,
      }));
    },
    closeDialog() {
      this.$emit("closeDialog");
      this.$emit("getProduct");
    },
    tambah() {
      this.selectProduct.push({
        kelompok_produk_id: "",
        merk: "",
        kode: "",
        nama: "",
        produk_id: "",
        no_akd: "",
        status: "1",
        generate_seri: "1",
        gudang_barang_jadi: [
          {
            stok: 0,
            stok_siap: 0,
            satuan_id: 2,
          },
        ],
      });
    },
    async simpan() {
      const isValid = await this.$refs.formProducts.validate();
      if (!isValid) return;
      try {
        this.loading = true;
        const { data } = await axios.post("/api/produk", this.selectProduct);
        this.closeDialog();
        this.$swal("Berhasil", "Produk berhasil ditambahkan", "success");
      } catch (error) {
        console.log(error);
        this.$swal("Gagal", "Produk gagal ditambahkan", "error");
      } finally {
        this.loading = false;
      }
    },
    removeProduk(item) {
      const index = this.selectProduct.indexOf(item);
      console.log(index);
      this.selectProduct.splice(index, 1);
    },
  },
  created() {
    this.getCategory();
  },
  watch: {
    status(val) {
      if (val == "1") {
        this.selectProduct.status = true;
      } else {
        this.selectProduct.status = false;
      }
    },
    generate_seri(val) {
      if (val == "1") {
        this.selectProduct.generate_seri = true;
      } else {
        this.selectProduct.generate_seri = false;
      }
    },
  },
};
</script>
<template>
  <div>
    <v-dialog v-model="dialogCreate" persistent max-width="70%">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Tambah Produk</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn @click="simpan" :loading="loading" :disabled="loading" text
            >Simpan</v-btn
          >
        </v-toolbar>

        <v-form ref="formProducts" v-model="valid" lazy-validation>
          <v-data-table :headers="header" :items="selectProduct">
            <template #top>
              <div class="d-flex justify-end">
                <v-btn class="mr-1 mt-1" color="primary" @click="tambah"
                  >Tambah</v-btn
                >
              </div>
            </template>

            <template #item.kelompok_produk_id="{ item }">
              <v-autocomplete
                class="mt-5"
                v-model="item.kelompok_produk_id"
                :items="kelompok"
                :rules="[rules.required]"
                outlined
                dense
              ></v-autocomplete>
            </template>

            <template #item.merk="{ item }">
              <v-autocomplete
                class="mt-5"
                v-model="item.merk"
                :items="merk"
                :rules="[rules.required]"
                outlined
                dense
              ></v-autocomplete>
            </template>

            <template #item.kode="{ item }">
              <v-text-field
                class="mt-5"
                v-model="item.kode"
                @input="item.kode = item.kode.toUpperCase()"
                :rules="[rules.kodeUnique(item.id, item.kode)]"
                outlined
                dense
              ></v-text-field>
            </template>

            <template #item.nama="{ item }">
              <v-text-field
                class="mt-5"
                v-model="item.nama"
                @input="item.nama = item.nama.toUpperCase()"
                :rules="[rules.required, rules.nameUnique(item.id, item.nama)]"
                outlined
                dense
              ></v-text-field>
            </template>

            <template #item.kategori="{ item }">
              <v-autocomplete
                class="mt-5"
                v-model="item.produk_id"
                :items="category"
                :rules="[rules.required]"
                outlined
                dense
              ></v-autocomplete>
            </template>

            <template #item.no_akd="{ item }">
              <v-text-field
                type="number"
                class="mt-5"
                v-model="item.no_akd"
                :rules="[rules.mustBeNumber]"
                outlined
                dense
              ></v-text-field>
            </template>

            <template #item.status="{ item }">
              <v-switch
                class="mt-5 mx-1"
                v-model="item.status"
                color="primary"
              ></v-switch>
            </template>

            <template #item.generate_seri="{ item }">
              <v-switch
                class="mt-5 mx-1"
                v-model="item.generate_seri"
                color="primary"
              ></v-switch>
            </template>

            <template #item.aksi="{ item }">
              <v-btn icon @click="removeProduk(item)" v-if="!item.id">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </template>
          </v-data-table>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>
