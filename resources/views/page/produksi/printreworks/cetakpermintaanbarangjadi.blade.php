<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .text-center {
            text-align: center;
        }

        .judul {
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            text-decoration: underline;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col {
            flex: 0 0 50%;
            max-width: 50%;
        }

        table {
            width: 100%;
        }

        .td-full-border {
            border: 1px solid black;
        }

        .td-urutan {
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            padding-top: -10px;
        }
    </style>
</head>

<body>
    <h1 class="judul">PERMINTAAN BARANG JADI</h1>

    <table>
        <tr>
            <td style="width: 22%">No. Permintaan</td>
            <td style="width: 1%">:</td>
            <td style="width: 22%">0001/X/2003</td>
            <td style="width: 25%"></td>
            <td class="text-center td-full-border">No. Referensi</td>
        </tr>
        <tr>
            <td>Tanggal Permintaan</td>
            <td>:</td>
            <td>0001/X/2003</td>
            <td style="width: 25%"></td>
            <td class="text-center td-urutan"><b>PRD-1</b></td>
        </tr>
        <tr>
            <td>Tanggal Digunakan</td>
            <td>:</td>
            <td>0001/X/2003</td>
        </tr>
        <tr>
            <td>Bagian</td>
            <td>:</td>
            <td>0001/X/2003</td>
        </tr>
        <tr>
            <td>Kegunaan untuk</td>
            <td>:</td>
            <td>0001/X/2003</td>
        </tr>
    </table>
</body>

</html>
