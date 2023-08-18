<!DOCTYPE html>
<html lang="en">
<head>
    <style>
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
            border: 1px solid black;
        }
        .table>thead>tr>th{
            border: 1px solid black;
        }
        .table>tbody>tr>td{
            border: 1px solid black;
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
            padding-top: 100px;
        }
        /* Define styles for the footer */
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Apply styles only when printing */
        @media print {
            footer {
                display: block;
                position: fixed;
                bottom: 0;
            }
        }

        body {
            font-size: 14px;
        }

    </style>
</head>
<body>
    <header>
        <table class="text-right">
            <tr>
                <td></td>
                <td><b>Surabaya, {{ \Carbon\Carbon::now()->isoFormat('Do MMMM YYYY') }}</b></td>
            </tr>
            <tr>
                <td></td>
                <td><b>Kepada Yth. UP. {{ $data->up }} </b></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>No SJ: {{$data->nosj}}</td>
                <td></td>
            </tr>
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
                    <th>No</th>
                    <th>Jumlah</th>
                    <th>Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $data->item as $key => $item)
                <tr class="text-center">
                    <td >{{ $key+1 }}</td>
                    <td>
                        @if(isset($item->noseri))
                        {{$item->jumlah_noseri}}
                        @else
                        {{$item->jumlah}}
                        @endif
                    </td>
                    <td>{{ $item->nama }}</td>
                </tr>
                @if(isset($item->noseri)){
                <tr>
                    <td colspan="3">
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
            <tr>
                <td style="width: 40%">Penerima,</td>
                <td>Hormat Kami,</td>
            </tr>
            <tr>
                <td class="all-text mt-td">(Nama Terang & Stempel Perusahaan)</td>
                <td>
                    <table class="table">
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
        </table>
    </footer>
</body>
</html>