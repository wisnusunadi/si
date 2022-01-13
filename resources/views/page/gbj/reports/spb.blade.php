<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<style>
    .judul{
        font-size: 24px;
        text-align: center;
    }
    .box{
        border: 5px double black;
        padding: 10px;
    }
    body{
        font-family: "Times New Roman";
        font-size: 16px;
    }
    .kepada{
        margin-right: 250px;
    }
    .di{
        margin-right: 250px;
    }
    .margin_bottom{
        border-bottom: 1px solid black;
    }
    .table-awal{
        margin: 100px;
    }
    .table, .td, .th{
        border: 1px solid black;
    }
    .table{
        border-collapse: collapse;
        width: 100%;
        text-align: center;
    }
    .table1{
        margin: 20px 0px;
    }
    .table2{
        width: 100%;
        text-align: center;
    }
    .table2-tr{
        padding-bottom: 50px;
    }
    img{
        width: 150px;
    }
    .text-right{
        text-align: right;
        margin-top: 0px;
    }
</style>
<body>

    <div class="box">
        <p class="judul"><strong><u>Surat Pengantar Barang</u></strong></p>
        <table class="table1">
            @foreach ($header as $h)
            <tr>
                <td>Ref / P.O</td>
                <td>:</td>
                <td><u>{{ $h->pesanan->so }}</u></td>
            </tr>

            <tr>
                <td>Distributor</td>
                <td>:</td>
                @php
                    $name = explode('/', $h->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        echo '<td><u>'.$h->pesanan->Ekatalog->Customer->nama.'</u></td>';
                    } elseif ($name[1] == 'SPA') {
                        echo '<td><u>'.$h->pesanan->Spa->Customer->nama.'</u></td>';
                    } elseif ($name[1] == 'SPB') {
                        echo '<td><u>'.$h->pesanan->Spb->Customer->nama.'</u></td>';
                    }
                @endphp

            </tr>
            <tr>
                <td colspan="3">Harap barang-barang yang tertulis di bawah ini diserahkan</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>:</td>
                <td class="margin_bottom"><span class="kepada"></span><span class="di"> {{ $h->divisi->nama }}</span></td>
            </tr>
            @endforeach
        </table>
        <table class="table">
            <thead> 
                <tr>
                    <th class="th" style="width: 5%;">Banyaknya</th>
                    <th class="th">Nama Barang</th>
                    <th class="th">Nomor Seri</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $s)
                @php
                    $no = 1;
                @endphp
                    @foreach ($s->seri as $ss)
                    <tr>
                        <td class="td">{{ $s->qty }}</td>
                        <td class="td">{{ $s->produk->produk->nama }} {{ $s->produk->nama }}</td>
                        <td class="td">{{ $ss->seri->noseri }}</td>
                    </tr>
                    @endforeach

                @endforeach
            </tbody>
        </table>
        <table class="table2">
            <tr>
                <td>Diserahkan oleh,</td>
                <td>Diperiksa oleh,</td>
                <td>Diterima oleh,</td>
            </tr>
            <tr class="table2-tr">
                <td><img src="{{ asset('assets/image/accepted.png') }}" alt=""></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                @foreach ($tfby as $t)
                <td><u>{{ $t->transfer_by == null ? '-' : $t->transfer->nama }}</u><br>{{ $t->transfer->Divisi->nama }}</td>
                <td><u>{{ $t->check_by == null ? '-' : $t->check->nama }}</u><br>{{ $t->check_by == null ? '-' : $t->check->Divisi->nama }}</td>
                <td><u>{{ $t->terima_by == null ? '-' : $t->terima->nama }}</u><br>{{ $t->terima_by == null ? '-' : $t->terima->Divisi->nama }}</td>

            </tr>
        </table>
    </div>
    <p class="text-right">Nomor Dokumen : SPA-FR/GUD-07(Nomor Dokumen) Tanggal Terbit : {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $t->created_at)->isoFormat('D MMMM Y') }}</p>
    @endforeach
</body>
</html>
