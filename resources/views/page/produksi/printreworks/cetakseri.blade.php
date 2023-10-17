<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp

    <div class="image-container">
        <span>Elitech</span><br>
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($seri, $generator::TYPE_CODE_128, 2, 50)) }}" />
        <div class="text-center">{{ $seri }}</div>
    </div>
</body>

</html>
