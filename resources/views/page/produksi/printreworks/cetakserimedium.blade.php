<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            margin-left: 0.8cm;
        }
        .font-size-elitech {
            font-size: 13pt;
        }

        /* medium */
        /* ukuran 100 x 50 */
        @page {
            margin-top: 0.1cm;
            font-size: 12pt;
        }

        .image-container-logo-11 {
            /* margin-left: -0.85cm; */
            /* antro */
            margin-left: -0.75cm;
        }

        .image-container-logo-12 {
            margin-left: -0.86cm;
        }

        .image-container-logo-13 {
            margin-left: -1.5cm;
        }

        .image-container-logo-14 {
            margin-left: -1.12cm;
        }

        .image-container-11 {
            margin-left: -0.58cm;
        }

        .image-container-12 {
            margin-left: -0.8cm;
        }

        .image-container-13 {
            margin-left: -0.85cm;
        }

        .image-container-14 {
            margin-left: -1cm;
        }

        .logo {
            margin-top: 0.2cm;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
@foreach ($data as $item)
@php
$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
$merkLogo = '';

switch ($isLogo) {
    case 'elitech':
        $merkLogo = 'Elitech';
        break;
    case 'vanward':
        $merkLogo = 'Vanward';
        break;
    case 'rgb':
        $merkLogo = 'RGB';
        break;
    case 'mentor':
        $merkLogo = 'Mentor';
        break;
    default:
        $merkLogo = '';
        break;
}

$sizeHeight = $merkLogo != '' ? 29 : 33;


$classLogo = '';

switch (strlen($item->noseri)) {
    case 11:
        $classLogo = 'image-container-logo-11';
        $sizeWidth =  $merkLogo != '' ? 0.97 : 0.91;
        break;
    case 12:
        $classLogo = 'image-container-logo-12';
        $sizeWidth =  $merkLogo != '' ? 0.96 : 0.91;
        break;
    case 13:
        $classLogo = 'image-container-logo-13';
        $sizeWidth =  $merkLogo != '' ? 0.96 : 0.91;
        break;
    default:
        $classLogo = 'image-container-logo-14';
        $sizeWidth =  $merkLogo != '' ? 0.96 : 0.91;
        break;
}

$classNotLogo = '';

switch (strlen($item->noseri)) {
    case 11:
        $classNotLogo = 'image-container-11';
        break;
    case 12:
        $classNotLogo = 'image-container-12';
        break;
    case 13:
        $classNotLogo = 'image-container-13';
        break;
    default:
        $classNotLogo = 'image-container-14';
        break;
}

@endphp

<div class="{{ $loop->last ? '' : 'page-break' }}">
    <div class="{{  $merkLogo != '' ? $classLogo : $classNotLogo }}">
        <span class="{{ $merkLogo != '' ? 'font-size-elitech' : 'logo' }}">{{ $merkLogo }}</span>
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item->noseri, $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}" />
        <div class="text-center">{{ $item->noseri }}</div>
    </div>
</div>
@endforeach
</body>

</html>
