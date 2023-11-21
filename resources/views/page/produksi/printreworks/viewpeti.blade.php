<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Peti</title>

    <style>
        .text-center {
            text-align: center;
        }

        /* position all to center */
        .image-container {
            display: flex;
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        .box {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        
    .first-item {
        margin-bottom: 30px;
    }

    .last-item {
        margin-top: 30px;
    }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="width: 80%">
                <h1>ANTROPOMETRI KIT 10</span>
            </td>
            <td style="width: 10%"></td>
            <td style="width: 15%">
                <div class="box">PETI - 1000</div>
            </td>
        </tr>
    </table>

    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

        $seri = ['TD08217A4235', 'TD08217A4258', 'TD08217A4207'];
    @endphp

        @foreach ($seri as $index => $s)
        <div class="image-container {{ $index === 0 ? 'first-item' : '' }} {{ $index === count($seri) - 1 ? 'last-item' : '' }}">
            <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($s, $generator::TYPE_CODE_128_B, 2, 60)) }}"
                class="text-center" />
            <div class="text-center">{{ $s }}</div>
        </div>
    @endforeach
</body>

</html>
