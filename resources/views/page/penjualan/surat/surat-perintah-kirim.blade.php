<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:400,600,700,800">
        <title>Surat Perintah Pengiriman Barang</title>
        <style>
          /** Define the margins of your page **/
          .full-page-border {
              height: 100%;
              width: 100%;
              border: 1px solid black;
              border-collapse: collapse;
          }
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
              top: 160px;
              width: 100%;
              font-family: sans-serif;
              font-size: 14px;
              padding-top: 30px;

          }
          header {
              position: fixed;
              top: -60px;
              left: 0px;
              right: 0px;
              height: 220px;
              font-family: sans-serif;
              font-size: 14px;
              /** Extra personal styles **/
            background-color: #ffffff;
              color: rgb(0, 0, 0);
              line-height: 20px;
          }

          .table_footer{
              position:  fixed;
              bottom: -45px;
              font-family: sans-serif;
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
          border-top: 1px solid #000000;
          border-bottom: 1px solid #000000;
          border-collapse: collapse;
          margin: 0;
          }

          #invoice-table tbody {
            min-height: 500px;
          }
          table {
            table-layout: fixed; width: 100%;
          }
          .td-width-header {
            width: 35%;
          }
          </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <table id="tabel" class="table table-hover styled-table table-striped" style="">
                <tr>
                  <td style="text-align: left; font-size: 18px;">
                    <b>PERINTAH PENGIRIMAN BARANG</b>
                  </td>
                  <th style="text-align: right; padding-right:10px; font-size: 18px;">
                    PT. Sinko Prima Alloy
                  </td>
                </tr>
            </table>
            <hr>
            <table id="tabel" class="table table-hover styled-table table-striped " style="word-wrap:break-word;">
                <tr>
                  <td style="text-align: left;" class="vera" >
                      <table  style="">
                          <tr>
                              <td class="vera"  width="23%">Pelanggan
                                :
                            </td>
                              <td><b><u>{{$data['customer']}}</u></b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td>{{$data['alamat_customer']}}</td>
                          </tr>
                      </table>
                      <br>
                      <table  style="">
                          <tr>
                              <td class="vera"   width="23%">Pengiriman :</td>
                              <td><b><u>{{$data['tujuan_kirim']}}</u></b></td>
                            </tr>
                          <tr>
                              <td ></td>
                              <td>{{$data['alamat_kirim']}}</td>
                          </tr>

                      </table>
                  </td>
                  <td width="2%"></td>
                  <td class="vera "  width="40%">
                    <table style="width: 100%">
                      <tr>
                        <td class="td-width-header">Nomor SO</td>
                        <td>: {{$data['so']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Tanggal SO</td>
                        <td>: {{$data['tgl_so']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Nomor PO</td>
                        <td>: {{$data['no_po']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Tanggal PO</td>
                        <td>: {{$data['tgl_po']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Kemasan</td>
                        <td>: {{$data['kemasan']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Tgl Pengiriman</td>
                        <td>: {{$data['tgl_kirim']}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Ekspedisi</td>
                        <td>: {{$data['ekspedisi']}}</td>
                      </tr>
                    </table>

                    </tr>
                </table>
            </header>


        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- Hal -1 --}}
            @foreach ($data['item'] as $key_page => $page)
            <table id="invoice-table" class="table table-hover styled-table table-striped" style="table-layout: fixed; width: 100%; ">
                <thead class="border-collapse: collapse;">
                   <tr>
                     <th class="align-center" width="8%" style="border-bottom: 1px solid black">
                       <b>No</b>
                     </th>
                     <th class="align-center" width="15%" style="border-bottom: 1px solid black">
                       <b>Kode Barang</b>
                     </th>
                     <th class="align-center" style="border-bottom: 1px solid black">
                       <b>Nama Barang</b>
                     </th>
                     <th class="align-center"  width="8%" style="border-bottom: 1px solid black">
                       <b>Jumlah</b>
                     </th>
                     <th class="align-center"  width="8%" style="border-bottom: 1px solid black">
                       <b>Satuan</b>
                     </th>
                     <th class="align-center"  width="8%" style="border-bottom: 1px solid black">
                       <b>Pajak</b>
                     </th>
                   </tr>
                </thead>
                <tbody >
                @foreach ($page as $key_produk => $item)
                    <tr>
                        <td class="vera align-center">
                        {{$item['no']}}
                        </td>
                        <td class="vera align-center">
                            {{$item['kode']}}
                        </td>
                        <td class="vera">
                            {{$item['nama']}}
                        </td>
                        <td class="vera align-center">
                            {{$item['jumlah']}}.00
                        </td>
                        <td class="vera align-center">
                            {{$item['satuan']}}
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
            <table id="tabel" class="table table-hover styled-table table-striped">
                <tr>
                  {{-- <td class="align-left vera" width="13%">

                  </td> --}}
                  <td class="align-left" >
                    <b>Keterangan :</b> <br>
                    {{$data['no_paket']}}
                    @if ($data['ket_paket'] != null)
                     - {{$data['ket_paket']}}
                    @else
                    <br>
                    @endif
                  </td>
                </tr>
            </table>
            <hr>
            <table id="tabel" class="table table-hover styled-table table-striped" border="0" style="">
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
                    {{-- Penjualan --}}
                  </td>
                  <td class="align-center">
                    <hr style="width:50%">
                    {{-- Logistik --}}
                  </td>
                </tr>
                <td class="align-right" colspan="2" >
                    <br>
                 <tr>
                <tr>
                  <td class="align-right" colspan="2" style="font-size: 12px">
                    <i>SPA-FR/01/PENJ-02, Tanggal Terbit: 10 November 2021, Revisi: 01</i>
                  </td>

                </tr>
            </table>
        </footer>
                <div style="page-break-after: never;"> </div>
            @else

                <table id="tabel" class="table table-hover styled-table table-striped table_footer" border="0" style="">
                    <tr>
                      <td class="align-right" colspan="2">
                    <i>SPA-FR/01/PENJ-02, Tanggal Terbit: 10 November 2021, Revisi: 01</i>
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
