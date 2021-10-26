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
  <div>
    <div class="table-responsive">
      <table class="table table-hover text-center" width="100%">
        <thead style="text-align: center; font-size: 15px">
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Waktu</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
            <td>100</td>
            <td>2021-09-01 - 2021-09-03</td>
            <td>
              <span class="badge badge-pill badge-warning">Perencanaan</span>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>MEDICAL APRON</td>
            <td>100</td>
            <td>2021-09-04 - 2021-09-07</td>
            <td>
              <span class="badge badge-pill badge-warning">Perencanaan</span>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>TIMBANGAN BAYI MEKANIK</td>
            <td>50</td>
            <td>2021-09-08 - 2021-09-10</td>
            <td>
              <span class="badge badge-pill badge-warning">Perencanaan</span>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>BABY SCALE</td>
            <td>200</td>
            <td>2021-10-01 - 2021-10-03</td>
            <td>
              <span class="badge badge-pill badge-info">Pelaksanaan</span>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>MEDICAL APRON</td>
            <td>100</td>
            <td>2021-10-04 - 2021-10-07</td>
            <td>
              <span class="badge badge-pill badge-info">Pelaksanaan</span>
            </td>
          </tr>
          <tr>
            <td>6</td>
            <td>TIMBANGAN BAYI MEKANIK</td>
            <td>50</td>
            <td>2021-10-08 - 2021-10-10</td>
            <td>
              <span class="badge badge-pill badge-info">Pelaksanaan</span>
            </td>
          </tr>
          <tr>
            <td>7</td>
            <td>BABY SCALE</td>
            <td>200</td>
            <td>2021-11-01 - 2021-11-03</td>
            <td><span class="badge badge-pill badge-success">Selesai</span></td>
          </tr>
          <tr>
            <td>8</td>
            <td>MEDICAL APRON</td>
            <td>100</td>
            <td>2021-11-04 - 2021-11-07</td>
            <td><span class="badge badge-pill badge-success">Selesai</span></td>
          </tr>
          <tr>
            <td>9</td>
            <td>TIMBANGAN BAYI MEKANIK</td>
            <td>50</td>
            <td>2021-11-08 - 2021-11-10</td>
            <td><span class="badge badge-pill badge-success">Selesai</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>