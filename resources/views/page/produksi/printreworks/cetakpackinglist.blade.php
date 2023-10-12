<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Packing List</title>

    <style>
        .judul {
            font-family: 'Book Antiqua';
            font-size: 16pt;
            text-align: center;
        }

        header table {
            font-family: 'Times New Roman';
            font-size: 12pt;
            font-weight: bold;
        }

        header table tr {
            padding: 40px 0;
        }
        .text-center {
            text-align: center;
        }

        main table {
            /* all bordered */
            border-collapse: collapse;
            border: 1px solid black;
            font-family: 'Times New Roman';
            font-size: 12pt;
        }

        main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        table {
            width: 100%;
        }
    </style>
</head>

<body>
    <p class="judul">Packing List</p>

    <header>
        <table>
            <tr>
                <td>Product Name</td>
                <td>:</td>
                <td>Antropometri</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>:</td>
                <td>Antropometri KIT 10</td>
            </tr>
            
            <tr>
                <td>Serial Number</td>
                <td>:</td>
                <td>TAS9898989898</td>
            </tr>
        </table>
    </header>

    <main>
        <table>
            <thead class="text-center">
                <tr>
                    <td>Item</td>
                    <td>No.</td>
                    <td>Contents</td>
                    <td>Qty</td>
                    <td>Remark</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td  class="text-center">Standart Configuration</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>DIGIT PRO BABY</td>
                    <td class="text-center">1</td>
                    <td>SN12345678</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>USB CABLE</td>
                    <td class="text-center">1</td>
                    <td>SN12345678</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>CD</td>
                    <td class="text-center">1</td>
                    <td>SN12345678</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>
