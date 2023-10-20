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

        header table,
        main table
        {
            width: 100%;
        }

        header table tr td {
            padding: 5px;
        }

        .td-full-border {
            border: 1px solid black;
        }

        .td-urutan {
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        /* full border table */
        main table {
            border-collapse: collapse;
            border: 1px solid black;
        }

        main table tr th, main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        footer {
            position: fixed;
            bottom: 0;
        }

        .catatan-table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .catatan-table tr td {
            padding: 5px;
        }

        .table-dibuat {
            width: 20%;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1 class="judul">PERMINTAAN BARANG JADI</h1>

    <header>
        <table>
            <tr>
                <td style="width: 22%">No. Permintaan</td>
                <td style="width: 1%">:</td>
                <td style="width: 40%">0001/X/2003</td>
                <td style="width: 15%"></td>
                <td class="text-center td-full-border">No. Referensi</td>
            </tr>
            <tr>
                <td>Tanggal Permintaan</td>
                <td>:</td>
                <td>18 Oktober 2023</td>
                <td style="width: 15%"></td>
                <td class="text-center td-urutan"><b>PRD-1</b></td>
            </tr>
            <tr>
                <td>Tanggal Digunakan</td>
                <td>:</td>
                <td>18 Oktober 2023</td>
            </tr>
            <tr>
                <td>Bagian</td>
                <td>:</td>
                <td>Produksi</td>
            </tr>
            <tr>
                <td>Kegunaan untuk</td>
                <td>:</td>
                <td>Rework Produksi Antropometri Kit 10</td>
            </tr>
        </table>
    </header>

    <main>
        <table class="text-center">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>656356</td>
                    <td>Antropometri Kit 10</td>
                    <td>100</td>
                </tr>
                <tr>
                    <td>656356</td>
                    <td>Antropometri Kit 10</td>
                    <td>100</td>
                </tr>
            </tbody>
        </table>
    </main>

    <footer>
        <table class="catatan-table">
            <tr>
                <td>Catatan :</td>
            </tr>
            <tr>
                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias nesciunt ex ratione hic iste adipisci minus. Eligendi, repudiandae, nemo suscipit consectetur deleniti nobis esse doloribus eos distinctio cupiditate at. Ab.</td>
            </tr>
        </table>

        <table class="text-center table-dibuat">
            <tr>
                <td style="width: 60%">Dibuat Oleh,</td>
            </tr>
            <tr>
                <td style="padding-top: 50px;">
                    <hr>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
