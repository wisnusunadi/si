<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: 'Times New Roman';
            font-size: 11pt;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .judul {
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            text-decoration: underline;
        }

        table {
            width: 100%;
        }

        header {
            margin-top: 20px;
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

        main table tr th,
        main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        footer {
            position: fixed;
            bottom: 0;
        }

        .utama {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .judul-utama {
            border-left: 1px solid black;
            border-right: 1px solid black;
        }

        .utama-dokumen {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .utama-dokumen-td {
            padding: 10px 0px 10px 5px;
        }

        .small-text {
            font-size: 9pt;
        }

        .table-dibuat {
            padding-left: 10%;
        }
    </style>
</head>

<body>
    <table class="utama">
        <tr>
            <td class="td-padding text-center" rowspan="4">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/assets/image/sinko_logo.png'))) }}"
                    width="85">
            </td>
            <td class="judul-utama" rowspan="4">
                <h1 class="judul">PENGANTAR BARANG JADI</h1>
            </td>
            <td colspan="3">
        <tr>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;">No Dokumen</td>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;">:</td>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;"> SPA-FR/GUD-7</td>
        </tr>
        <tr>
            <td class="small-text utama-dokumen utama-dokumen-td">Revisi</td>
            <td class="small-text utama-dokumen utama-dokumen-td">:</td>
            <td class="small-text utama-dokumen utama-dokumen-td"> 00</td>
        </tr>
        <tr>
            <td class="small-text utama-dokumen-td">Tgl. Terbit</td>
            <td class="small-text utama-dokumen-td">:</td>
            <td class="small-text utama-dokumen-td"> 14 Maret 2020</td>
        </tr>
        </td>
        </tr>
    </table>

    <header>
        <table>
            <tr>
                <td style="width: 15%">No. Surat</td>
                <td style="width: 35%">: FPBJ/X/23/000001</td>
                <td style="width: 30%"></td>
                <td class="text-center td-full-border">No. Referensi</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: 18 Oktober 2023</td>
                <td style="width: 15%"></td>
                <td class="text-center td-urutan"><b>00001/X/2023</b></td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>: Produksi</td>
            </tr>
        </table>
    </header>

    <main>
        <table class="text-center">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Banyaknya</th>
                    <th>Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 5%">1</td>
                    <td style="width: 20%">5 Unit</td>
                    <td>Antropometri Kit 10</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-left"><b>No Seri</b> : Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Impedit, earum magni consectetur cupiditate odit omnis quam, debitis nam officia doloribus
                        doloremque aspernatur id provident, architecto dolores obcaecati minima animi quo?</td>
                </tr>
            </tbody>
        </table>
    </main>

    <footer>
        <table class="text-center table-dibuat">
            <tr>
                <td style="width: 200px">Diserahkan Oleh,</td>
            </tr>
            <tr>
                <td style="padding-top: 70px;">
                    Ikut Login GBJ
                    <hr>
                </td>
            </tr>
        </table>


    </footer>
</body>

</html>
