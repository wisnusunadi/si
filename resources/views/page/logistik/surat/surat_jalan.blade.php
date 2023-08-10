<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            main {
                position: absolute;
                top: 150px;
                width: 100%;
            }
            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 220px;

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
                top: 700px;
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
                              <td style=" border: 1px solid;"><b>{{$data->customer}}</b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td style=" border: 1px solid;">{{$data->alamat_customer}}</td>
                          </tr>
                      </table>
                      <br>
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"   width="20%">Alamat Pengiriman :</td>
                              <td style=" border: 1px solid;"  class="vera" ><b>{{$data->tujuan_kirim}}</b></td>
                          </tr>
                          <tr>
                             <td></td>
                              <td style=" border: 1px solid;">{{$data->alamat_kirim}}</td>
                          </tr>

                      </table>
                  </td>
                  <td width="1%"></td>
                  <td style="text-align: left;" class="vera"  width="28%">
                      <table border="1"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>No SJ</u> <br>
                                  {{$data->nosj}}
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl SJ</u><br>
                                  {{$data->tgl_sj}}
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>No PO</u> <br>
                                  {{$data->no_po}}
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl PO</u><br>
                                  {{$data->tgl_po}}
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera ">
                                  <u>Ekspedisi</u> <br>
                                  {{$data->ekspedisi}}
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl Pengiriman</u><br>
                                  {{$data->tgl_kirim}}
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera " colspan="2">
                                  <u>Up</u> <br>
                                  {{$data->up}}
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
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type
                  </td>
                </tr>
            </table>
            <br>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td class="align-center">
                    Dibuat Oleh,
                  </td>
                  <td class="align-center">
                Diterima Oleh,
                  </td>
                </tr>
                <td class="align-right" colspan="2" >
                   <br>
                   <br>
                   <br>

                <tr>
                  <td class="align-center">
                    <hr style="width:50%">
                    Penjualan
                  </td>
                  <td class="align-center">
                    <hr style="width:50%">
                    Logistik
                  </td>
                </tr>
                <td class="align-right" colspan="2" >
                    <br>


                 <tr>
                <tr>
                  <td class="align-right" colspan="2">
                <i>No Dokumen : SPA-FR/01/asas/asas/asas/2023</i>
                  </td>

                </tr>
            </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- Hal -1 --}}

                <table id="tabel" class="table table-hover styled-table table-striped" border="1" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                    <thead>
                       <tr>
                         <td class="align-center" width="8%">
                           <b>No</b>
                         </td>
                         <td class="align-center" >
                           <b>Kode Barang</b>
                         </td>
                         <td class="align-center">
                           <b>Nama Barang</b>
                         </td>
                         <td class="align-center"  width="8%">
                           <b>Jumlah</b>
                         </td>
                         <td class="align-center"  width="8%">
                           <b>Satuan</b>
                         </td>
                         <td class="align-center" >
                           <b>No Seri</b>
                         </td>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach ( $data->item as $item )
                        <tr>
                            <td class="align-center">
                            {{$item->no}}
                            </td>
                            <td class="align-center">
                                {{$item->kode}}
                            </td>
                            <td class="align-center">
                                {{$item->nama}}
                            </td>
                            <td class="align-center">
                                {{$item->jumlah}}.00
                            </td>
                            <td class="align-center">
                                {{$item->satuan}}
                            </td>
                            <td class="align-center">
                             @php echo implode(', ',$item->noseri) @endphp

                            </td>
                          </tr>

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
            {{-- Hal -2 --}}

                {{-- <div style="page-break-after: never;"> </div> --}}
        </main>
    </body>
</html>
