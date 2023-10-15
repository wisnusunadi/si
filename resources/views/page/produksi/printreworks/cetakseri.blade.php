<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>

    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp

    <span>Elitech</span><br>
    <div class="container">
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($seri, $generator::TYPE_CODE_128, 2, 50)) }}" />
        <div class="bottom-center-text">{{ $seri }}</div>
    </div>

</body>

</html>
