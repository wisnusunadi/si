<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:400,600,700,800">
        <style>
            /** Define the margins of your page **/
            @media print {
                body {
                    margin-left: 3cm; /* Sesuaikan angka ini sesuai dengan kebutuhan Anda */
                }
            }
            @page {

              margin :  70px 30px 70px 30px
            }

            .text-center {
                text-align: center;
            }
            main {
                position: absolute;
                top: 198px;
                width: 100%;
                font-family: sans-serif;
                font-size: 13px;
            }
            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 220px;
                font-family: sans-serif;

                /** Extra personal styles **/
             background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
            }

            .table_footer{
                position: fixed;
                bottom: -45px;
                font-family: sans-serif;
            }
            footer {
                position: fixed;
                bottom: -10px;
                left: 0px;
                right: 0px;
                height: 50px;
                top: 730px;
                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
                font-family: sans-serif;
                font-size: 16px;
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
    .wb {
            word-break: break-word;
        }
    .page-break {
            page-break-after: always;
        }
        #invoice-table {
            border: 0.5pt solid rgb(0, 0, 0);
            border-collapse: collapse;

        }


        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td style="text-align: left;">
                    <b>PERINTAH PENGIRIMAN BARANG</b>
                  </td>
                  <th style="text-align: right; padding-right:10px">
                    PT. Sinko Prima Alloy
                  </td>
                </tr>
            </table>
            <table id="tabel" class="table table-hover styled-table table-striped " border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; word-wrap:break-word;">
                <tr>
                  <td style="text-align: left;" class="vera" >
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"  width="23%">Pelanggan

                                :
                            </td>
                              <td style=" border: 1px solid;"><b>{{$data['customer']}}</b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td style=" border: 1px solid;">{{$data['alamat_customer']}}</td>
                          </tr>
                      </table>
                      <br>
                      <table border="0"  style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                          <tr>
                              <td class="vera"   width="23%">Alamat Pengiriman </td>
                              <td style=" border: 1px solid;"><b>{{$data['tujuan_kirim']}}</b></td>
                            </tr>
                          <tr>
                              <td ></td>
                              <td style=" border: 1px solid;">{{$data['alamat_kirim']}}</td>
                          </tr>

                      </table>
                  </td>
                  <td width="2%"></td>
                  <td style="text-align: left;" class="vera "  width="31%">
                      <table border="1"  style="table-layout: fixed; width: 100%; border-collapse: collapse;  ">
                          <tr>
                              <td style=" border: 1px solid;" class="vera ">
                                  <u>No SO</u> <br>
                                 <div class="text-center">{{$data['so']}}</div>
                              </td>
                              <td style=" border: 1px solid;" class="vera ">
                                  <u>Tgl SO</u><br>
                                  {{-- change date format dd/mm/yyyy --}}
                                 <div class="text-center">{{ date('d/m/Y', strtotime($data['tgl_so'])) }}</div>
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid; " class="vera">
                                  <u>No PO</u> <br>
                                 <div class="text-center">{{$data['no_po']}}</div>
                              </td>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Tgl PO</u><br>
                                <div class="text-center">{{ date('d/m/Y', strtotime($data['tgl_po'])) }}</div>
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera">
                                  <u>Kemasan</u> <br>
                               <div class="text-center">{{$data['kemasan']}}</div>
                              </td>
                              <td style=" border: 1px solid;" class="vera ">
                                  <u>Tgl Pengiriman</u><br>
                                <div class="text-center">{{$data['tgl_kirim']}}</div>
                              </td>
                          </tr>
                          <tr>
                              <td style=" border: 1px solid;" class="vera " colspan="2">
                                  <u>Ekspedisi</u> <br>
                                 <div class="text-center">{{$data['ekspedisi']}}</div>
                              </td>

                          </tr>
                      </table>
                      </tr>
                      </table>
        </header>


        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- Hal -1 --}}
            @foreach ($data['item'] as $key_page => $page)
            <table id="invoice-table" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; ">
                <thead class="border-collapse: collapse; border-left: 1px solid black; border-left: 1px solid black">
                   <tr>
                     <th class="align-center" width="8%"  style="border-right:    1px solid black ; border-bottom:    1px solid black">
                       <b>No</b>
                     </th>
                     <th class="align-center" width="15%"  style="border-right:    1px solid black ; border-bottom:    1px solid black">
                       <b>Kode Barang</b>
                     </th>
                     <th class="align-center"  style="border-right:    1px solid black ; border-bottom:    1px solid black">
                       <b>Nama Barang</b>
                     </th>
                     <th class="align-center"  width="8%"  style="border-right:    1px solid black ; border-bottom:    1px solid black">
                       <b>Jumlah</b>
                     </th>
                     <th class="align-center"  width="8%"  style="border-right:    1px solid black; border-bottom:    1px solid black">
                       <b>Satuan</b>
                     </th>
                     <th class="align-center"  width="8%"  style="border-bottom:    1px solid black">
                       <b>Pajak</b>
                     </th>
                   </tr>
                </thead>
                <tbody>
                    @foreach ($page as $key_produk => $item)
                    <tr>
                        <td class="vera align-center" style="border-right:    1px solid black ; " >
                        {{$item['no']}}
                        </td>
                        <td class="vera align-center" style="border-right:    1px solid black ; ">
                            {{$item['kode']}}
                        </td>
                        <td class="vera align-center" style="border-right:    1px solid black ; ">
                            {{$item['nama']}}
                        </td>
                        <td class="vera align-center" style="border-right:    1px solid black ; ">
                            {{$item['jumlah']}}.00
                        </td>
                        <td class="vera align-center" style="border-right:    1px solid black ; ">
                         UNIT
                        </td>
                        <td class="vera align-center">
                            {{$item['pajak']}}
                        </td>
                      </tr>
                      @endforeach
                </tbody>
            </table>

            @if ($key_page == $count_page - 1)
            <footer>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                <tr>
                  <td class="align-left vera" width="12%">
                    Keterangan
                  </td>
                  <td class="align-left"  style=" border: 1px solid;">
                    {{$data['no_paket']}}
                    <br>
                    {{$data['ket_paket']}}
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
                <i>No Dokumen : SPA-FR/01/asomeseasomese/2023</i>
                  </td>

                </tr>
            </table>
        </footer>
                <div style="page-break-after: never;"> </div>
            @else

                <table id="tabel" class="table table-hover styled-table table-striped table_footer" border="0" style="table-layout: fixed; width: 100%; border-collapse: collapse; ">
                    <tr>
                      <td class="align-right" colspan="2">
                    <i>No Dokumen : SPA-FR/01/asomeseasomese/2023</i>
                      </td>

                    </tr>
                </table>


                <div style="page-break-after: always;"></div>
            @endif

            @endforeach

            {{-- </div>  --}}
                {{-- <div style="page-break-after: always;"></div> --}}
            {{-- Hal -2 --}}
                {{-- <div style="page-break-after: never;"> </div> --}}
        </main>
        {{-- <footer>

      </footer> --}}

    </body>
</html>



{{-- $pesanan = Karyawan::limit(24)->get();
// $dateOfBirth = $karyawan_sakit->karyawan->tgllahir;
// $umur = Carbon::parse($dateOfBirth)->age;
// $carbon = Carbon::now();
// $footer = Carbon::createFromFormat('Y-m-d', $karyawan_sakit->tgl_cek)->isoFormat('D MMMM Y');
 $customPaper = array(0,0,609.44,788.031);
// $pdf = PDF::loadView('page.kesehatan.surat_sakit', ['karyawan_sakit' => $karyawan_sakit])->setPaper($customPaper);
// return $pdf->stream('');

foreach($pesanan as $key => $k){
    $pesanan_arr[$key] = array(
        'no' => $key+1,
        'id' => $k->id,
        'nama' => $k->nama
    );
}
$data = array_chunk($pesanan_arr, 12);
$pdf = PDF::loadView('page.kesehatan.surat_sakit', ['data' => $data,'count_page' => count($data)])->setOptions(['defaultFont' => 'sans-serif'])->setPaper($customPaper);


return $pdf->stream(''); --}}
