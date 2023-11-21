<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Peti</title>

    <style>

        /* position all to center */
        .image-container {
            margin-left: 23%
        }

        .image-container > .text-center {
            margin-left: 22%
        }

        table {
            width: 100%;
            margin-bottom: 5px;
        }

        .box {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        
    .first-item {
        margin-bottom: 25px;
    }

    .last-item {
        margin-top: 25px;
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
                {{-- <div class="box">PETI - 1000</div> --}}
            </td>
        </tr>
    </table>

    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

        $seri = ['AK1023A000003', 'AK1023B009994', 'AK1023B000005'];
    @endphp

        @foreach ($seri as $index => $s)
        <div class="image-container {{ $index === 0 ? 'first-item' : '' }} {{ $index === count($seri) - 1 ? 'last-item' : '' }}">
            <span>Elitech</span> <br>
            <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($s, $generator::TYPE_CODE_128_B, 2, 60)) }}" />
            <div class="text-center">{{ $s }}</div>
        </div>
    @endforeach
</body>

</html>
