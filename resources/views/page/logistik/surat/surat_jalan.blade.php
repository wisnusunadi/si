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
                              <td style=" border: 1px solid;"><b>PT Emiindo Jaya Bersama</b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td style=" border: 1px solid;">Komplek Perkantoran Pulomas Jalan Perintis Kemerdekaan 10 No. 8, pulo Gadung, Jakarta Timur, DKI Jakarta</td>
                          </tr>
                      </table>
                      <br>
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"   width="20%">Alamat Pengiriman :</td>
                              <td style=" border: 1px solid;"  class="vera" ><b>PT Mahkota Anugrah Karya</b></td>
                          </tr>
                          <tr>
                             <td></td>
                              <td style=" border: 1px solid;">Komplek Perkantoran Pulomas Jalan Perintis Kemerdekaan 10 No. 8, pulo Gadung, Jakarta Timur, DKI Jakarta</td>
                          </tr>

                      </table>
                  </td>
                  <td width="1%"></td>
                  <td style="text-align: left;" class="vera"  width="28%">
                      <table border="1"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>No SJ</u> <br>
                                  asd/df/09/87/22
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl SJ</u><br>
                                 23/12/2023
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>No PO</u> <br>
                                  asd/df/09/87/22
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl PO</u><br>
                                 23/12/2023
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera ">
                                  <u>Ekspedisi</u> <br>
                                Non Peti
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl Pengiriman</u><br>
                                 23/12/2023
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera " colspan="2">
                                  <u>Up</u> <br>
                              Bu Apriana - 08523901234
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
                         <td class="align-center" width="20%">
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
                         <td class="align-center"  width="8%">
                           <b>Pajak</b>
                         </td>
                       </tr>
                    </thead>
                    <tbody>
                        @for ($i=1;$i<=8;$i++)
                        <tr>
                            <td class="align-center">
                             {{ $i}}
                            </td>
                            <td class="align-center">
                              Richard McClintock
                            </td>
                            <td class="align-center">
                                Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock
                            </td>
                            <td class="align-center">
                            123.00
                            </td>
                            <td class="align-center">
                             UNIT
                            </td>
                            <td class="align-center">
                            PPn
                            </td>
                          </tr>

                        @endfor

                        <tr>
                            <td class="align-center" colspan="3">

                            </td>
                            <td class="align-center" colspan="3">
                              <b>Total 1 Coly</b>
                            </td>


                          </tr>
                    </tbody>
                </table>
                {{-- <div style="page-break-after: always;"></div> --}}
            {{-- Hal -2 --}}

                {{-- <div style="page-break-after: never;"> </div> --}}
        </main>
    </body>
</html>
