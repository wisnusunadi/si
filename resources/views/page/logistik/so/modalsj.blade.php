<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  th.hidden {
    display: none;
  }

  .labelket {
    text-align: right;
  }
</style>
<div class="modal fade" id="cetaksjmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cetak Surat Jalan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5>Data PIC</h5>
                <div class="form-group row">
                  <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">Nama PIC</label>
                  <div class="col-lg-6 col-md-12">
                    <input type="text" class="form-control" name="nama_pic" id="">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">Nomor Telepon PIC</label>
                  <div class="col-lg-6 col-md-12">
                    {{-- input with number only --}}
                    <input type="text" class="form-control" name="telp_pic" id="" onkeypress="return isNumberKey(event)">
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h5>Data Pengiriman</h5>
                <div class="form-horizontal">
                  <div class="form-group row">
                    <label for="invoice_jenis" class="col-form-label col-lg-5 col-md-12 labelket">Nomor</label>
                    <div class="col-lg-6 col-md-12 col-form-label">
                      <div class="form-check form-check-inline">
                        <input type="radio" name="sj_jenis" id="sj_jenis1" class="form-check-input" value="new" checked>
                        <label for="sj_jenis1" class="form-check-label">Surat Jalan Baru</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input type="radio" name="sj_jenis" id="sj_jenis2" class="form-check-input" value="old">
                        <label for="sj_jenis2" class="form-check-label">Surat Jalan Tersedia</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No
                        Surat Jalan</label>
                    <div class="col-lg-6 col-md-12">
                        <div class="input-group mb-3 sj_baru" id="sj_baru" name="sj_baru">
                            <div class="input-group-prepend">
                                <select class="form-control jenis_sj" name="jenis_sj" id="jenis_sj">
                                    <option value="SPA-">SPA-</option>
                                    <option value="B.">B.</option>
                                    <option value="NBT">NBT</option>
                                </select>
                            </div>
                            <input type="text" class="form-control col-form-label" name="no_invoice"
                                id="no_invoice">
                            <div class="invalid-feedback" id="msgnoinvoice"></div>
                        </div>
                        <span class="sj_lamas hide">
                            <select class="form-control sj_lama" name="sj_lama" id="sj_lama"
                                placeholder="Pilih No Surat Jalan">
                                <option value=""></option>
                            </select>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-5 col-md-12 labelket"
                        for="tgl_kirim">Tanggal Pengiriman</label>
                    <div class="col-lg-4 col-md-6">
                        <input type="date" class="form-control col-form-label" name="tgl_kirim"
                            id="tgl_kirim">
                        <div class="invalid-feedback" id="msgtgl_kirim"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for=""
                        class="col-form-label col-lg-5 col-md-12 labelket">Pengiriman</label>
                    <div class="col-lg-5 col-md-12 col-form-label">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengiriman"
                                id="pengiriman1" value="ekspedisi" />
                            <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengiriman"
                                id="pengiriman2" value="nonekspedisi" />
                            <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row hide" id="ekspedisi">
                    {{-- <div class="card col-lg-12 hide" id="ekspedisi"> --}}
                    {{-- <div class="card-body">
                                <h6><b>Ekspedisi</b></h6> --}}
                    {{-- <div class="form-group row"> --}}
                    <label class="col-form-label col-lg-5 col-md-12 labelket"
                        for="ekspedisi_id">Jasa Pengiriman</label>
                    <div class="col-lg-7 col-md-12">
                        <select class="select2 select-info form-control ekspedisi_id"
                            name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">

                        </select>
                        <div class="invalid-feedback" id="msgekspedisi_id"></div>
                        <label for="" id="ekspedisi_nama" class="col-form-label hide"></label>
                    </div>
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
                <div class="form-group row hide" id="nonekspedisi">
                    {{-- <div class="card col-12 hide" id="nonekspedisi"> --}}
                    {{-- <div class="card-body">
                                <h6><b>Non Ekspedisi</b></h6> --}}
                    {{-- <div class="form-group row"> --}}
                    <label class="col-form-label col-lg-5 col-md-12 labelket"
                        for="nama_pengirim">Nama Pengirim</label>
                    <div class="col-lg-7 col-md-12">
                        <textarea type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim"></textarea>
                        <div class="invalid-feedback" id="msgnama_pengirim"></div>
                    </div>
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
                <div class="form-group row">
                  <label for="" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Pengiriman</label>
                    <div class="col-lg-6 col-md-12 col-form-label">
                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pengiriman0" value="penjualan" />
                        <label for="pengiriman0" class="form-check-label">Sama dengan Penjualan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pengiriman1" value="lainnya" />
                        <label for="pengiriman1" class="form-check-label">Ubah Alamat</label>
                    </div>
                    <input type="text" name="perusahaan_pengiriman" id="perusahaan_pengiriman" class="form-control col-form-label" readonly>
                    <input type="text"
                        class="form-control col-form-label mt-2" name="alamat_pengiriman" id="alamat_pengiriman" readonly/>
                    <div class="invalid-feedback"
                        id="msg_alamat_pengiriman">
                    </div>
                  </div>
                </div>
                  <div class="form-group row">
                    <label for="" class="col-lg-5 col-md-12 col-form-label labelket">Kemasan</label>
                    <div class="col-lg-6 col-md-12 col-form-label">

                      <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input" name="kemasan" id="kemasan0" value="peti" />
                          <label for="kemasan0" class="form-check-label">PETI</label>
                      </div>
                      <div class="form-check form-check-inline">
                          <input type="radio" class="form-check-input" name="kemasan" id="kemasan1" value="nonpeti" />
                          <label for="kemasan1" class="form-check-label">NON PETI</label>
                      </div>
                    </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-lg-5 col-md-12 labelket"
                      for="nama_pengirim">Keterangan Pengiriman</label>
                  <div class="col-lg-7 col-md-12">
                      <select name="keterangan_pengiriman" class="form-control">
                        <option value="bayar_tujuan">BAYAR TUJUAN</option>
                        <option value="bayar_sinko">BAYAR SINKO</option>
                        <option value="non_bayar">NON BAYAR</option>
                      </select>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-produk-tab" data-toggle="pill" data-target="#pills-produk" type="button" role="tab" aria-controls="pills-produk" aria-selected="true">Produk</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link disabled" id="pills-part-tab" data-toggle="pill" data-target="#pills-part" type="button" role="tab" aria-controls="pills-part" aria-selected="false">Part</a>
                  </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade show active" id="pills-produk" role="tabpanel" aria-labelledby="pills-produk-tab">
                    <table class="table tableproduk" width="100%">
                      <thead>
                        <tr>
                            <th>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="check_all" id="check_all" name="check_all" />
                                  <label class="form-check-label" for="check_all">
                                  </label>
                              </div>
                          </th>
                          <th>Nama Produk</th>
                          <th>Jumlah</th>
                          <th>Aksi</th>
                          <th class="hidden"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="pills-part" role="tabpanel" aria-labelledby="pills-part-tab">...</div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button type="button" class="btn btn-primary cetaksjkirim">
            <i class="fa fa-print"></i> Cetak
          </button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalnoseri" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nomor Seri</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="index">
          {{-- text upppercase --}}
          <textarea class="form-control" id="noseritext" cols="30" rows="10"
          onkeyup="this.value = this.value.toUpperCase();"
          ></textarea>
          <small class="form-text text-muted">
            Silahkan masukkan tiap nomor seri dengan dipisahkan dengan koma (,)
          </small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button type="button" class="btn btn-primary simpannoseri">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).on('click', '.noseri', function(){
      let index = $(this).data('index');
      let noseri = $('.keterangannoseri'+index).val();

      $('#noseritext').val(noseri);
      $('#index').val(index);
      $('#modalnoseri').modal('show');
    });

    $(document).on('click', '.simpannoseri', function () {
      let index = $('#index').val();
      let noseri = $('#noseritext').val();
      let keterangan = $('.keterangannoseri'+index);
      let jumlah = $('.jumlah'+index);

      keterangan.val(noseri);
      // remove spasi
      noseri = noseri.replace(/\s/g, '');
      // split by koma
      noseri = noseri.split(',');
      // remove empty string
      noseri = noseri.filter(function (el) {
        return el != "";
      });
      // jumlahkan
      jumlah.val(noseri.length);

      console.log(noseri);
      $('#modalnoseri').modal('hide');
    })

    $('input[name="pengiriman"]').on('change', function () {
      let val = $(this).val();
      if (val == 'ekspedisi') {
        $('#ekspedisi').removeClass('hide');
        $('#nonekspedisi').addClass('hide');
      } else {
        $('#ekspedisi').addClass('hide');
        $('#nonekspedisi').removeClass('hide');
      }
    });

    const isNumberKey = (event) => {
      const charCode = (event.which) ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
    }

    $(document).on('click', '#check_all', function () {
      if (this.checked) {
        $(document).find('.check_detail').each(function () {
          this.checked = true;
        });
      } else {
        $(document).find('.check_detail').each(function () {
          this.checked = false;
        });
      }
    });

    $(document).on('click', '.cetaksjkirim', function () {
    // find data on datatable is checked
        let data = [];
        let table = $('.tableproduk').DataTable();
        let check = $(document).find('.check_detail');

        // push data to array
        for (let i = 0; i < check.length; i++) {
            if (check[i].checked) {
                let row = table.row(i).node(); // Get the row node directly
                let rowIndex = table.row(i).index();
                let jumlahValue = $('.jumlah' + rowIndex).val(); // Access the input value directly
                let keteranganValue = $('.keterangannoseri' + rowIndex).val(); // Access the hidden input value directly

                let rowData = table.row(row).data();
                rowData['jumlah_noseri'] = jumlahValue; // Update the 'jumlah_noseri' property with the input value
                rowData['noseri_selected'] = keteranganValue; // Update the 'noseri_selected' property with the hidden input value

                data.push(rowData);
            }
        }

        if(data.length == 0){
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Tidak ada data yang dipilih!',
          })
          return false;
        }


    });

    $(document).on('change', 'input[type="radio"][name="pilihan_pengiriman"]', function () {
      let pilihan_pengiriman = $(this).val();
      $('#perusahaan_pengiriman').attr('readonly', true);
      $('#alamat_pengiriman').attr('readonly', true);
      $('#perusahaan_pengiriman').val('');
            // add placeholder
      $('#perusahaan_pengiriman').attr('placeholder', 'Masukkan Nama Perusahaan');
      $('#alamat_pengiriman').val('');
      $('#alamat_pengiriman').removeClass('is-invalid');
      // add placeholder
      $('#alamat_pengiriman').attr('placeholder', 'Masukkan Alamat Pengiriman');

      if(pilihan_pengiriman == 'lainnya'){
        $('#perusahaan_pengiriman').val('');
        $('#alamat_pengiriman').val('');
        $('#perusahaan_pengiriman').attr('readonly', false);
        $('#alamat_pengiriman').attr('readonly', false);
      }
    });

    $(document).on('change', 'input[name=sj_jenis]', function () {
      let sj_jenis = $(this).val();
      if(sj_jenis == 'old'){
        $('.sj_lamas').removeClass('hide');
        $('.sj_baru').addClass('hide')
      }else{
        $('.sj_lamas').addClass('hide');
        $('.sj_baru').removeClass('hide')
      }
    });

  </script>

