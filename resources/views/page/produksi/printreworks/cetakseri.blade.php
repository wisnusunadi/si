<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* large */
        /* @page {
            margin-top: 0.08cm;
            margin-left: 0.5cm;
        } */

        /* medium */
        /* @page {
            margin-top: 0.2cm;
            margin-left: 0.2cm;
        } */

        /* small */
        @page {
            margin-top: -0.01cm;
            margin-left: 0.9cm;
            font-size: 5pt
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

{{-- ulangi 5 kali --}}
     @for ($i = 0; $i < 1; $i++)
     {{-- large --}}
    {{-- <div class="image-container">
        <span>Elitech</span><br>
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($seri, $generator::TYPE_CODE_128_B, 2, 29)) }}" />
        <div class="text-center">{{ $seri }}</div>
    </div> --}}
     {{-- medium --}}
     {{-- <div class="image-container">
        <span>Elitech</span><br>
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($seri, $generator::TYPE_CODE_128_B, 0.95, 29)) }}" />
        <div class="text-center">{{ $seri }}</div>
    </div> --}}
     {{-- small --}}
      <div class="image-container">
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($seri, $generator::TYPE_CODE_128_B, 0.80, 24)) }}" />
        <div class="text-center">{{ $seri }}</div>
    </div>
    @endfor
</body>

</html>
