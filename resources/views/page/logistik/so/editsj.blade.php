<div class="modal fade" id="editsj" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Surat Jalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="formcetaksj">
                    <div class="card">
                      <div class="card-body">
                        <h5>Data PIC</h5>
                        <div class="hide">
                          <input type="text" name="pesanan_id" id="">
                          <input type="text" name="so" id="">
                          <input type="text" name="no_po" id="">
                          <input type="text" name="tgl_po" id="">
                          <input type="text" name="nama_customer" id="">
                          <input type="text" name="alamat_customer" id="">
                          <input type="text" name="provinsi_id" id="provinsi_id_top">
                        </div>
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
                      <div class="form-group row form-provinsi">
                          <label class="col-form-label col-lg-5 col-md-12 labelket"
                              for="tgl_kirim">Provinsi</label>
                              <div class="col-lg-5 col-md-12 col-form-label">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="provinsi"
                                        id="provinsi1" value="provinsi_customer" />
                                    <label class="form-check-label" for="provinsi1">Distributor</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="provinsi"
                                        id="provinsi2" value="provinsi_instansi" />
                                    <label class="form-check-label" for="provinsi_instansi">Instansi</label>
                                </div>
                                <select name="provinsi" class="form-control provinsi_pengiriman">
                                </select>
                                <div class="hidden dataprovinsiekat"></div>
                            </div>
                      </div>
                        <div class="form-group row">
                            <label for=""
                                class="col-form-label col-lg-5 col-md-12 labelket">Pengiriman</label>
                            <div class="col-lg-5 col-md-12 col-form-label">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pengiriman_surat_jalan"
                                        id="pengiriman1" value="ekspedisi" />
                                    <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pengiriman_surat_jalan"
                                        id="pengiriman2" value="nonekspedisi" />
                                    <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="ekspedisi">
                            {{-- <div class="card col-lg-12 hide" id="ekspedisi"> --}}
                            {{-- <div class="card-body">
                                        <h6><b>Ekspedisi</b></h6> --}}
                            {{-- <div class="form-group row"> --}}
                            <label class="col-form-label col-lg-5 col-md-12 labelket"
                                for="ekspedisi_id">Jasa Pengiriman</label>
                            <div class="col-lg-7 col-md-12">
                                <select class="select2 select-info form-control ekspedisi_id"
                                    name="ekspedisi" id="ekspedisi_id" style="width: 100%;">
      
                                </select>
                                <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                <label for="" id="ekspedisi_nama" class="col-form-label hide"></label>
                            </div>
                            {{-- </div> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                        </div>
                        <div class="form-group row" id="nonekspedisi">
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
                          <label for="dimensi" class="col-form-label col-lg-5 col-md-12 labelket">Ekspedisi Terusan</label>
                          <div class="col-lg-7 col-md-12">
                            <textarea type="text" class="form-control col-form-label" name="ekspedisi_terusan" id="dimensi"></textarea>
                            <div class="invalid-feedback" id="msgnama_pengirim"></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Pengiriman</label>
                            <div class="col-lg-6 col-md-12 col-form-label">
                              <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pilihan_pengiriman0" value="penjualan" />
                                <label for="pengiriman0" class="form-check-label">Sama dengan Penjualan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pilihan_pengiriman1" value="lainnya" />
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
                          <label for="dimensi" class="col-form-label col-lg-5 col-md-12 labelket">Dimensi</label>
                          <div class="col-lg-7 col-md-12">
                            <textarea type="text" class="form-control col-form-label" name="dimensi" id="dimensi"></textarea>
                            <div class="invalid-feedback" id="msgnama_pengirim"></div>
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
                  </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>