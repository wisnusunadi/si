<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";

export default {
  data: function () {
    return {
      filterData: [],
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },

    filter: function (status) {
      if (this.filterData.length === 0) return true;

      for (let i = 0; i < this.filterData.length; i++) {
        if (status === this.filterData[i]) return true;
      }
      return false;
    },
  },

  mounted: function () {
    $("#perakitan-table").DataTable({});
  },
};
</script>

<template>
  <div>
    <div>
      <div class="dropdown show">
        <button
          class="btn btn-outline-primary dropdown-toggle mb-3"
          data-toggle="dropdown"
        >
          Filter
        </button>

        <div class="dropdown-menu p-3">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="perencanaan"
              id="defaultCheck1"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck1">
              Perencanaan
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="pelaksanaan"
              id="defaultCheck2"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck2">
              Pelaksanaan
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="selesai"
              id="defaultCheck3"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck3">
              Selesai
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="perakitan-table"
            class="table table-hover text-center"
            width="100%"
          >
            <thead style="text-align: center; font-size: 15px">
              <tr align="center">
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Produk</th>
                <th rowspan="2">Jumlah</th>
                <th colspan="2">Waktu</th>
                <th rowspan="2">Progres</th>
                <th rowspan="2">Status</th>
              </tr>
              <tr>
                <th>Mulai</th>
                <th>Selesai</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="this.filter('perencanaan')">
                <td>1</td>
                <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                <td>100</td>
                <td>2021-09-01</td>
                <td>2021-09-03</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="20"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 20%"
                    ></div>
                  </div>
                  <small> 20% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-warning"
                    >Perencanaan</span
                  >
                </td>
              </tr>
              <tr v-if="this.filter('perencanaan')">
                <td>2</td>
                <td>MEDICAL APRON</td>
                <td>100</td>
                <td>2021-09-04</td>
                <td>2021-09-07</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="0"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 0%"
                    ></div>
                  </div>
                  <small> 0% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-warning"
                    >Perencanaan</span
                  >
                </td>
              </tr>
              <tr v-if="this.filter('perencanaan')">
                <td>3</td>
                <td>TIMBANGAN BAYI MEKANIK</td>
                <td>50</td>
                <td>2021-09-08</td>
                <td>2021-09-10</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="0"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 0%"
                    ></div>
                  </div>
                  <small> 0% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-warning"
                    >Perencanaan</span
                  >
                </td>
              </tr>
              <tr v-if="this.filter('pelaksanaan')">
                <td>4</td>
                <td>BABY SCALE</td>
                <td>200</td>
                <td>2021-10-01</td>
                <td>2021-10-03</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="50"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 50%"
                    ></div>
                  </div>
                  <small> 50% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-info">Pelaksanaan</span>
                </td>
              </tr>
              <tr v-if="this.filter('pelaksanaan')">
                <td>5</td>
                <td>MEDICAL APRON</td>
                <td>100</td>
                <td>2021-10-04</td>
                <td>2021-10-07</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="50"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 50%"
                    ></div>
                  </div>
                  <small> 50% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-info">Pelaksanaan</span>
                </td>
              </tr>
              <tr v-if="this.filter('pelaksanaan')">
                <td>6</td>
                <td>TIMBANGAN BAYI MEKANIK</td>
                <td>50</td>
                <td>2021-10-08</td>
                <td>2021-10-10</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="50"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 50%"
                    ></div>
                  </div>
                  <small> 50% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-info">Pelaksanaan</span>
                </td>
              </tr>
              <tr v-if="this.filter('selesai')">
                <td>7</td>
                <td>BABY SCALE</td>
                <td>200</td>
                <td>2021-11-01</td>
                <td>2021-11-03</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="100"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 100%"
                    ></div>
                  </div>
                  <small> 100% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-success">Selesai</span>
                </td>
              </tr>
              <tr v-if="this.filter('selesai')">
                <td>8</td>
                <td>MEDICAL APRON</td>
                <td>100</td>
                <td>2021-11-04</td>
                <td>2021-11-07</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="100"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 100%"
                    ></div>
                  </div>
                  <small> 100% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-success">Selesai</span>
                </td>
              </tr>
              <tr v-if="this.filter('selesai')">
                <td>9</td>
                <td>TIMBANGAN BAYI MEKANIK</td>
                <td>50</td>
                <td>2021-11-08</td>
                <td>2021-11-10</td>
                <td>
                  <div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="100"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: 100%"
                    ></div>
                  </div>
                  <small> 100% Complete </small>
                </td>
                <td>
                  <span class="badge badge-pill badge-success">Selesai</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>