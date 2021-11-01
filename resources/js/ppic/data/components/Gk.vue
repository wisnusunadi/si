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
    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs">
          <li class="nav-item" @click="changeView('unit')">
            <a href="#chart-view" :class="['nav-link', { active: isUnit() }]"
              >Unit</a
            >
          </li>
          <li class="nav-item" @click="changeView('part')">
            <a href="#calendar-view" :class="['nav-link', { active: isPart() }]"
              >Part</a
            >
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div :class="['tab-pane fade', { 'show active': isUnit() }]">
            <div class="table-responsive">
              <table class="table table-hover text-center" width="100%">
                <thead style="text-align: center; font-size: 15px">
                  <tr>
                    <th>No</th>
                    <th>Kode Unit</th>
                    <th>Stok</th>
                    <th>Detail</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>BJAA01NB001</td>
                    <td><span style="color: green">1000</span></td>
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
                    <td><span style="color: red">5</span></td>
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
                    <td>
                      <span style="color: rgba(0, 0, 0, 0.4)">0</span>
                    </td>
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
          <div :class="['tab-pane fade', { 'show active': isPart() }]">
            <div class="table-responsive">
              <div class="table-responsive">
                <table class="table table-hover text-center" width="100%">
                  <thead style="text-align: center; font-size: 15px">
                    <tr>
                      <th>No</th>
                      <th>Kode Part</th>
                      <th>Stok</th>
                      <th>Detail</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>1231231</td>
                      <td><span style="color: green">1000</span></td>
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
                      <td>12323412</td>
                      <td><span style="color: red">5</span></td>
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
                      <td>34212313</td>
                      <td>
                        <span style="color: rgba(0, 0, 0, 0.4)">0</span>
                      </td>
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