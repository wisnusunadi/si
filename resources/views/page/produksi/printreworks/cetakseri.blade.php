<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .barcode-text {
            padding-left: 100px;
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
        <div class="barcode-text">{{ $seri }}</div>
    </div>
</body>

</html>
