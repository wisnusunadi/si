<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* small new */
        @page {
            margin-top: 0.55cm;
            margin-left: 0.5cm;
            margin-bottom: 0cm;
            font-size: 8pt;
        }

        .small-text {
            margin-left: 0.6cm;
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

    {{-- ulangi 5 kali --}}
    @foreach ($data as $item)
        <div class="image-container">
            <img
                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item['noseri'], $generator::TYPE_CODE_128_B, 0.7, 13)) }}" />
            <div class="small-text">{{ $item['noseri'] }}</div>
        </div>
    @endforeach
</body>

</html>
