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
            <tr>
                <td>Ref / P.O</td>
                <td>:</td>
                <td><u>nomor po</u></td>
            </tr>
            <tr>
                <td>Distributor</td>
                <td>:</td>
                <td><u>nama distributor</u></td>
            </tr>
            <tr>
                <td colspan="3">Harap barang-barang yang tertulis di bawah ini diserahkan</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>:</td>
                <td class="margin_bottom"><span class="kepada"></span><span class="di">di</span></td>
            </tr>
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
                <tr>
                    <td class="td">1</td>
                    <td class="td">SON-C</td>
                    <td class="td">FD421AA00671</td>
                </tr>
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
                <td><u>Nama User GBJ</u><br>Gudang Barang Jadi</td>
                <td><u>Nama User QC</u><br>Quality Control</td>
                <td><u>Nama User Logistik</u><br>Logistik</td>
            </tr>
        </table>
    </div>
    <p class="text-right">Nomor Dokumen : SPA-FR/GUD-07(Nomor Dokumen) Tanggal Terbit : (Tanggal Cetak)</p>
</body>
</html>