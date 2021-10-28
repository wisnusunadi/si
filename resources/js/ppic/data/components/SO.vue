<script>
export default {
  data: function () {
    return {
      show: false,
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },

    showCard: function (show) {
      this.show = show;
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
                <td>123</td>
                <td>SPA</td>
                <td>2021-10-10</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="showCard(true)"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>234</td>
                <td>SPA</td>
                <td>2021-11-11</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="showCard(true)"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>345</td>
                <td>SPA</td>
                <td>2021-12-12</td>
                <td>
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="showCard(true)"
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

    <div v-if="show" class="card">
      <div class="card-header">
        <div class="card-tools">
          <button type="button" class="btn btn-tool" @click="showCard(false)">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
            Distributor: test Instansi: test
            <address>
              <strong>Admin, Inc.</strong><br />
              795 Folsom Ave, Suite 600<br />
              San Francisco, CA 94107<br />
              Phone: (804) 123-5432<br />
              Email: info@almasaeedstudio.com
            </address>
          </div>
        </div>
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Produk 1</td>
                  <td>10</td>
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
                  <td>Produk 2</td>
                  <td>10</td>
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
                  <td>Produk 3</td>
                  <td>10</td>
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
    </div>

    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>No Seri</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1234</td>
                </tr>
                <tr>
                  <td>2345</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>