<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Packing List</title>

    <style>
        body {
            margin-left: 30%;
            margin-right: 30%;
        }

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
            font-size: 10pt;
        }

        main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        table {
            width: 100%;
        }

        .dokumen {
            text-align: right;
            font-family: 'Times New Roman';
            font-size: 8pt;
        }

        footer table {
            font-weight: bold;
        }

        .width-table {
            width: 150px;
        }
    </style>
</head>

<body>
    <p class="judul">Packing List</p>
    @php
        $dataItem = [
            [
                'product_name' => 'DIGIT PRO BABY',
                'quantity' => 1,
                'serial_number' => 'SN12345678',
            ],
            [
                'product_name' => 'USB CABLE',
                'quantity' => 1,
                'serial_number' => 'SN12345678',
            ],
            [
                'product_name' => 'CD',
                'quantity' => 1,
                'serial_number' => 'SN12345678',
            ],
            [
                'product_name' => 'CD',
                'quantity' => 1,
                'serial_number' => 'SN12345678',
            ],
        ];

        $dataNull = [
            [
                'product_name' => '',
                'quantity' => '',
                'serial_number' => '',
            ],
        ];

        // jika dataItem kurang dari 16, maka tambahkan dataNull hingga 16
        if (count($dataItem) < 6) {
            $dataNullIsi = [];
            for ($i = 0; $i < 6 - count($dataItem); $i++) {
                $dataNullIsi[] = $dataNull;
            }
            $dataItem = array_merge($dataItem, $dataNullIsi);
        }

        $optionsConfiguration = [];

        //
        for ($o = 0; $o < 6; $o++) {
            $optionsConfiguration[] = $dataNull;
        }
    @endphp
    <header>
        <table>
            <tr>
                <td class="width-table">Product Name</td>
                <td>:</td>
                <td>Antropometri</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>Model</td>
                <td>:</td>
                <td>Antropometri KIT 10</td>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td>Serial Number</td>
                <td>:</td>
                <td>TAS9898989898</td>
                <td colspan="3"></td>
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
                    <td rowspan="{{ count($dataItem) + 1 }}" class="text-center"><b>Standart Configuration</b></td>
                </tr>
                @foreach ($dataItem as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ isset($item['product_name']) ? $item['product_name'] : '' }}</td>
                        <td class="text-center">{{ isset($item['quantity']) ? $item['quantity'] : '' }}</td>
                        <td>{{ isset($item['serial_number']) ? $item['serial_number'] : '' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td rowspan="{{ count($optionsConfiguration) + 1 }}" class="text-center"><b>Options
                            Configuration</b>
                    </td>
                    @foreach ($optionsConfiguration as $options)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ isset($options['product_name']) ? $options['product_name'] : '' }}</td>
                    <td class="text-center">{{ isset($options['quantity']) ? $options['quantity'] : '' }}</td>
                    <td>{{ isset($options['serial_number']) ? $options['serial_number'] : '' }}</td>
                </tr>
                @endforeach
                </tr>
            </tbody>
        </table>
        <p class="dokumen">Nomor Dokumen: SPA-FR/QC-13, Tanggal Terbit : 04 Oktober 2018, Revisi : 00</p>
    </main>

    <footer>
        <table>
            <tr>
                <td class="width-table">Packing Date</td>
                <td>:</td>
                <td>12 Oktober 2023</td>
            </tr>
            <tr>
                <td>Packer</td>
                <td>:</td>
                <td>Orang Login</td>
                <td class="text-center">Checker</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/assets/image/accepted.png'))) }}"
                        width="125">
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center">PT Sinko Prima Alloy</td>
            </tr>
        </table>
    </footer>
</body>

</html>
