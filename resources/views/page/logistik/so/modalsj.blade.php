<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  th.hidden {
    display: none;
  }
</style>
<div class="modal fade" id="cetaksjmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                    <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No
                        Surat Jalan</label>
                    <div class="col-lg-6 col-md-12">
                        <div class="input-group mb-3 sj_baru" id="sj_baru" name="sj_baru">
                            <div class="input-group-prepend">
                                <select class="form-control jenis_sj" name="jenis_sj" id="jenis_sj">
                                    <option value="SPA">SPA</option>
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
                  <label class="col-form-label col-lg-5 col-md-12 labelket"
                      for="nama_pengirim">Keterangan Pengiriman</label>
                  <div class="col-lg-7 col-md-12">
                      <textarea type="text" class="form-control col-form-label" name="keterangan_pengiriman" id="keterangan_pengiriman"></textarea>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h5>Data Barang</h5>
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
                      <th>No</th>
                      <th>Nama Produk</th>
                      <th>Jumlah</th>
                      <th>Aksi</th>
                      <th class="hidden"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
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

        console.log(data);
    });

  </script>
  
