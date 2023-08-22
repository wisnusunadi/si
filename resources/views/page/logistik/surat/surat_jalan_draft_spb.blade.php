<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        @page {
            margin: 0px 20px 0px 13px;
            /* page-break-inside: avoid !important; */
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }
        header>table{
            width: 100%;
        }
        /* td first */
        .table-header-td-sm{
            width: 12%;
        }
        .table-header-td {
            width: 50%;
        }
        .td-width-header {
            width: 35%;
        }
        .table{
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        .table>thead>tr>th{
            border-bottom: 1px solid black;
        }
        .all-text{
            /* bold text */
            font-weight: bold;
            /* italic text */
            font-style: italic;
            /* underline text */
            text-decoration: underline;
        }
        footer>table{
            width: 100%;
        }
        /* margin top table ttd pengemudi*/
        .mt-td{
            padding-top: 60px;
        }
        /* Define styles for the footer */
        footer {
            position: fixed;
            bottom: 0;
            width: 98%;
        }
        .table-footer{
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }
        .table-footer>thead>tr>th{
            border: 1px solid black;
        }
        .table-footer>tbody>tr>td{
            border: 1px solid black;
        }

        main {
            margin-top: -10px;
        }

        hr {
            border: 0.5px solid black;
        }
        /* margin print on 9.5 * 5.5 */
        @media print {
            @page {
                size: 3.5in 0.5in landscape;
            }
        }

    </style>
</head>
<body>
    <header>
        <table style="font-size: 14px;">
            <tr>
              <td>
                <b>SURAT JALAN</b>
              </td>
              <th style="text-align: right;">
                PT. Sinko Prima Alloy
              </td>
            </tr>
        </table>
        <hr>
        <table class="table-header">
            <tr>
                <td class="table-header-td-sm">Tanggal SJ</td>
                <td>: {{ \Carbon\Carbon::parse($data->tgl_sj)->isoFormat('DD MMMM YYYY') }}</td>
                <td class="text-right"><b>Kepada Yth. {{ $data->customer }} </b></td>
            </tr>
            <tr>
                <td class="table-header-td-sm">No SJ</td>
                <td>: {{$data->nosj}}</td>
                <td class="text-right table-header-td"><b>UP. {{ $data->up}}</b></td>
            </tr>
            <tr>
                <td class="table-header-td-sm"
                style="vertical-align: top;"
                >PO</td>
                <td style="vertical-align: top;">: {{$data->no_po}}</td>
                <td class="text-right table-header-td"><b>{{ $data->alamat_customer}}</b></td>
            </tr>
        </table>
    </header>
    <main>
        <table class="table">
            <thead>
                <tr>
                    <th >No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $data->item as $key => $item)
                <tr class="text-center"
                @if(!isset($item->noseri))
                    style="border-bottom: 1px solid black"
                @endif
                >
                    <td >{{ $key+1 }}.</td>
                    <td class="text-left">{{ $item->nama }}</td>
                    <td>
                        @if(isset($item->noseri))
                        {{$item->jumlah_noseri}}.00
                        @else
                        {{$item->jumlah}}.00
                        @endif
                    </td>
                </tr>
                @if(isset($item->noseri)){
                <tr style="border-bottom: 1px solid black">
                    <td  ></td>
                    <td colspan="2">
                        <b>No Seri</b> :
                    @php echo implode(', ',$item->noseri) @endphp
                    </td>
                </tr>
                }
                @endif
                @endforeach
            </tbody>
            <tfoot class="text-center">
                @php
                    $totalproduk = 0;
                    $totalpart = 0;

                    foreach ($data->item as $item) {
                        if ($item->jenis === 'produk') {
                            $totalproduk += $item->jumlah_noseri;
                        } elseif ($item->jenis === 'part') {
                            $totalpart += $item->jumlah;
                        }
                    }

                    $total = $totalproduk + $totalpart;
                @endphp
                <tr>
                    <td colspan="3">Total : {{ $total }}.00 Unit</td>
                </tr>
            </tfoot>
        </table>
    </main>
    <footer>
        <b>Keterangan :</b>
        {{$data->paket}}
        @if ($data->ket != null)
            - {{$data->ket}}
        @else
        <br>
        @endif
        <table>
            <tr class="tr-first">
                <td style="width: 40%">Penerima,</td>
                <td>Hormat Kami,</td>
            </tr>
            <tr>
                <td class="all-text mt-td">(Nama Terang & Stempel Perusahaan)</td>
                <td>
                    <table class="table-footer">
                        <tbody class="text-center">
                            <tr>
                                <td>Pengemudi,</td>
                                <td style="width: 50%">Dibuat,</td>
                            </tr>
                            <tr >
                                <td class="mt-td"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right">
                    <span style="font-size: 4px"><i>SPA-FR/GUD-04, Tanggal Terbit : 20 Maret 2020, Revisi : 02</i></span>
                </td>
            </tr>
        </table>
    </footer>
    @if($data->dimensi != "")
        <div style="margin: 10px 0px;">
        <b>Dimensi</b>
        <br>
        {{ $data->dimensi}}
    @endif
    @if($data->ekspedisi_terusan != "")
        </div
        @if($data->dimensi == "")
        style="margin: 10px 0px;"
        @endif
        >
        <b>Ekspedisi Terusan : </b><br>
        {{ $data->ekspedisi_terusan}}
        <br>
        </div>
  @endif
</body>
</html>