<script>
export default {
  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },
  },
};
</script>

<template>
  <div class="table-responsive">
    <table class="table table-hover text-center semuaproduk" width="100%">
      <thead style="text-align: center; font-size: 15px">
        <tr>
          <th>No</th>
          <th>Kode Produk</th>
          <th>Stok</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>BJAA01NB001</td>
          <td><span class="float-right" style="color: green">1000</span></td>
          <td>
            <button class="btn btn-primary" @click="handleClick">
              <i class="fas fa-search"></i>
            </button>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>BJOZ03G0001</td>
          <td><span class="float-right" style="color: red">5</span></td>
          <td>
            <button class="btn btn-primary" @click="handleClick">
              <i class="fas fa-search"></i>
            </button>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>BJZZ03T0010</td>
          <td>
            <span class="float-right" style="color: rgba(0, 0, 0, 0.4)">0</span>
          </td>
          <td>
            <button class="btn btn-primary" @click="handleClick">
              <i class="fas fa-search"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

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
                  <th>No Seri</th>
                  <th>Deskripsi Kerusakan</th>
                  <th>Tingkat Kerusakan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1234</td>
                  <td>Rusak parah</td>
                  <td>s class</td>
                </tr>
                <tr>
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
<style lang="scss">
thead {
  text-align: center;
}
.semuaproduk {
  td:nth-child(1),
  td:nth-child(2),
  td:nth-child(3),
  td:nth-child(5),
  td:nth-child(7),
  td:nth-child(8) {
    text-align: center;
  }
}

.perproduk {
  td:nth-child(1),
  td:nth-child(2),
  td:nth-child(3),
  td:nth-child(6) {
    text-align: center;
  }
}

.pertanggal {
  td:nth-child(1),
  td:nth-child(2),
  td:nth-child(4),
  td:nth-child(7) {
    text-align: center;
  }
}

.center {
  width: 80%;
  margin-left: auto;
  margin-right: auto;
}
</style>