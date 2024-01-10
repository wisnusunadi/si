<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* small new */ 
        /* 45 x 36 : kertas kecil */
        @page {
            margin-top: 0.1cm;
            margin-left: 0.4cm;
            margin-bottom: 0cm;
            font-size: 7pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        .small-text {
            margin-left: 0.9cm;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

    {{-- ulangi 5 kali --}}
    @foreach ($data as $item)
    {{-- when last page not page break --}}
        <div class="{{ $loop->last ? '' : 'page-break' }}">
            <img
                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item, $generator::TYPE_CODE_93, 0.9, 16)) }}" />
            <div class="small-text">{{ $item }}</div>
        </div>
    @endforeach
</body>

</html>
