<html>
    <head>
        <style>

            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
                /* page-break-inside: avoid !important; */
            }


            main {
                position: relative;
                top: 190px;
                width: 100%;
                padding-bottom: 300px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 250px;
                margin-bottom: 100px;
                /** Extra personal styles **/
             background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
            }

            footer {

                position: fixed;
                bottom: -10px;
                left: 0px;
                right: 0px;
                height: 50px;
                top: 710px;
                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
            }


            .vera {
        vertical-align: top;
    }

    .align-left {
        text-align: left;
    }

    .align-right {
        text-align: right;
    }
    .align-center {
        text-align: center;
    }

    .page-break {
            page-break-after: always;
        }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td style="text-align: left;">
                    <b>SURAT JALAN</b>
                  </td>
                  <th style="text-align: right;">
                    PT. Sinko Prima Alloy
                  </td>
                </tr>
            </table>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">

                <tr>
                  <td style="text-align: left;" class="vera" >
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"  width="20%">Pelanggan :</td>
                              <td style=" border: 1px solid;"><b>
                                <?php if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                                    }
                                } else {

                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                                    }
                                }
                                ?>

                            </b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td style=" border: 1px solid;">
                                <?php if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                                    }
                                } else {

                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                                    }
                                }
                                ?>
                                </td>
                          </tr>
                      </table>
                      <br>
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"   width="20%">Alamat Pengiriman :</td>
                              <td style=" border: 1px solid;"  class="vera" ><b>
                                <?php if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                                    }
                                } else {

                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                                    }
                                }
                                ?>

                            </b></td>
                          </tr>
                          <tr>
                             <td></td>
                              <td style=" border: 1px solid;">
                                <?php if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                                    }
                                } else {

                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                                    }
                                }
                                ?></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td style=" border: 1px solid;"><b>UP : </b></td>
                          </tr>
                      </table>
                  </td>
                  <td width="1%"></td>
                  <td style="text-align: left;" class="vera"  width="28%">
                      <table border="1"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>No SJ</u> <br>
                               {{$data->nosurat}}
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl SJ</u><br>

                                  {{ \Carbon\Carbon::parse($data->tgl_kirim)->format('d M Y')}}
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;word-wrap: break-word" class="vera">
                                  <u>No PO</u> <br>
                                  @if (isset($data->DetailLogistik[0]))
                                    {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                  @else
                                    {{ $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po}}
                                  @endif

                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl PO</u><br>
                                  @if (isset($data->DetailLogistik[0]))
                                   {{ \Carbon\Carbon::parse($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po)->format('d M Y')}}
                                  @else
                                    {{ \Carbon\Carbon::parse($data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->tgl_po)->format('d M Y')}}
                                     @endif


                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera " colspan="2">
                                  <u>Ekspedisi</u> <br>
                                  @if ($data->nama_pengirim == '')
                                  {{$data->Ekspedisi->nama}}
                                  @else
                                  {{$data->nama_pengirim}}
                                  @endif

                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera " colspan="2">
                                  <u>Keterangan Pengiriman</u> <br>
                                    -
                              </td>

                          </tr>
                      </table>
                      </tr>
                      </table>
        </header>

        <footer>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td class="align-left vera" width="12%">
                    Keterangan
                  </td>
                  <td class="align-left"  style=" border: 1px solid;">
                    <?php if (isset($data->DetailLogistik[0])) {
                        $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                        if ($name[1] == 'EKAT') {
                            echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket;
                        }  else  {
                            echo 'OFFLINE';
                        }
                    } else {

                             echo 'OFFLINE';
                    }
                    ?>
                        <br>
                    <?php if (isset($data->DetailLogistik[0])) {
                        $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                        if ($name[1] == 'EKAT') {
                            echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                        }
                    }
                    ?>
                </td>
                </tr>
            </table>
            <br>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td class="align-center">
                    Diterima Oleh,
                  </td>
                  <td class="align-center">
                    Dibawa Oleh,
                  </td>
                  <td class="align-center">
                  Dibuat Oleh,
                  </td>
                </tr>
                <td class="align-right" colspan="2" >
                   <br>
                   <br>
                   <br>

                <tr>
                  <td class="align-center">
                    <hr style="width:30%">

                  </td>
                  <td class="align-center">
                    <hr style="width:40%">
                    {{-- KURIR --}}
                  </td>
                  <td class="align-center">
                    <hr style="width:30%">
                    {{-- LOGISTIK --}}
                  </td>
                </tr>
                <td class="align-right" colspan="3" >
                    <br>


                 <tr>
                <tr>
                  <td class="align-right" colspan="3">
                <i>No Dokumen : SPA-FR/GUD-04, Tanggal Terbit : 20 Maret 2020, Revisi : 02</i>
                  </td>

                </tr>
            </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- Hal -1 --}}
                <table id="tabel" class="table table-hover styled-table table-striped items" border="1" style="table-layout: fixed; width: 100%; border-collapse: collapse;   page-break-inside: avoid !important;">
                    <thead>
                       <tr>
                         <td class="vera align-center" width="8%">
                           <b>No</b>
                         </td>
                         <td class="vera align-center" width="20%">
                           <b>Kode Barang</b>
                         </td>
                         <td class="vera align-center">
                           <b>Nama Barang</b>
                         </td>
                         <td class="vera"  width="8%">
                           <b>Jumlah</b>
                         </td>
                         <td class="vera"  width="8%">
                           <b>Satuan</b>
                         </td>
                       </tr>
                    </thead>
                    <tbody style="page-break-after: avoid !important;">
                    @php $no = 0; @endphp
                       @foreach($data_produk as $e)
                        @if(isset($e->DetailPesananProduk))
                        @php $no =$loop->iteration; @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td></td>
                            <td class="wb align-left">
                                @if($e->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != NULL)
                                {{$e->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                                @else
                                {{$e->DetailPesananProduk->GudangBarangJadi->Produk->nama}} - {{$e->DetailPesananProduk->GudangBarangJadi->nama}}
                                @endif
                            </td>
                            <td class="nospace align-right">{{$e->NoseriDetailLogistik->count()}}.00</td>
                            <td>UNIT</td>
                        </tr>
                         <tr>
                        <td class="vera" colspan="5">
                            <b>No Seri</b> : <br>
                            @foreach($e->NoseriDetailLogistik as $x)
                            {{$x->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri}}
                            @if( !$loop->last)
                            ,
                            @endif
                            @endforeach
                        </td>
                        </tr>
                        @else
                        <tr>
                            <td>{{$loop->iteration + $no}}</td>
                            <td>{{$e->DetailPesananPart->Sparepart->kode}}</td>
                            <td class="wb align-left">
                                {{$e->DetailPesananPart->Sparepart->nama}}
                            </td>
                            <td class="nospace align-right">{{$e->DetailPesananPart->jumlah}}.00</td>
                            <td class="wb">
                                UNIT
                            </td>
                        </tr>
                        @endif
                        @endforeach



                        {{-- <tr>
                            <td class="align-center" colspan="3">

                            </td>
                            <td class="align-center" colspan="3">
                              <b>Total 1 Coly</b>
                            </td>


                          </tr> --}}
                    </tbody>
                </table>
                {{-- <div style="page-break-after: always;"></div> --}}
                {{-- @if($hal == $i)
                <div style="page-break-after: never;"> </div>
                @else
                <div style="page-break-after: always;"></div>
                @endif
                @endfor --}}

                {{-- <div style="page-break-after: always;"></div> --}}
            {{-- Hal -2 --}}

                {{-- <div style="page-break-after: never;"> </div> --}}
        </main>
    </body>
</html>
