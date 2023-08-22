<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        @page {
            margin: 5px 10px 0px 13px;
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
        .table-header-td {
            width: 50%;
        }
        .table{
            margin-top: 20px;
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
            bottom: 0;
            width: 100%;
        }
        footer>table {
            margin-top: 8px
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

    </style>
</head>
<body>
    <header>
        <table >
            <tr class="text-right">
                <td></td>
                <td><b>Surabaya, {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</b></td>
            </tr>
            <tr>
                <td>No SJ: {{$data->nosj}}</td>
                <td class="text-right"><b>Kepada Yth. UP. {{ $data->up }} </b></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>PO : {{$data->no_po}}</td>
                <td class="text-right table-header-td"><b>{{ $data->customer}}</b></td>    
            </tr>
            <tr>
                <td>DO : do-xxx</td>
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
                    <td >{{ $key+1 }}</td>
                    <td class="text-left">{{ $item->nama }}</td>
                    <td>
                        @if(isset($item->noseri))
                        {{$item->jumlah_noseri}}
                        @else
                        {{$item->jumlah}}
                        @endif
                    </td>
                </tr>
                @if(isset($item->noseri)){
                <tr style="border-bottom: 1px solid black">
                    <td  ></td>
                    <td colspan="2">
                        <b>No Seri</b> : <br>
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
                    <td colspan="3">Total : {{ $total }} Unit</td>
                </tr>
            </tfoot>
        </table>
    </main>
    <footer>
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
                    <span style="font-size: 8px"><i>SPA-FR/GUD-04, Tanggal Terbit : 20 Maret 2020, Revisi : 02</i></span>
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