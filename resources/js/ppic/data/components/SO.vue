<script>
export default {
  data: function () {
    return {
      view: "",
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },

    isPart: function () {
      return this.view === "part";
    },

    isUnit: function () {
      return this.view === "unit";
    },

    changeView: function (view) {
      this.view = view;
    },
  },

  mounted: function () {
    this.view = "unit";
  },
};
</script>

<template>
  <div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover text-center" width="100%">
            <thead style="text-align: center; font-size: 15px">
              <tr>
                <th>No</th>
                <th>No SO</th>
                <th>Jenis</th>
                <th>Batas Kontrak</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>BJAA01NB001</td>
                <td>1</td>
                <td>BJAA01NB001</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="handleClick"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>BJOZ03G0001</td>
                <td>2</td>
                <td>BJOZ03G0001</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="handleClick"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>BJZZ03T0010</td>
                <td>3</td>
                <td>BJZZ03T0010</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="handleClick"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>Distributor</th>
                  <th>Instansi</th>
                  <th>Alamat</th>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>No Seri</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1234</td>
                  <td>Rusak parah</td>
                  <td>s class</td>
                  <td>1234</td>
                  <td>Rusak parah</td>
                  <td>s class</td>
                </tr>
                <tr>
                  <td>2345</td>
                  <td>Rusak sedang</td>
                  <td>a class</td>
                  <td>2345</td>
                  <td>Rusak sedang</td>
                  <td>a class</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>