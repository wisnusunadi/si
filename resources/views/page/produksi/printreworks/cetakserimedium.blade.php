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

        .image-container-logo {
            /* margin-left: -0.85cm; */
            /* antro */
            margin-left: -0.9cm;
        }

        .image-container {
            margin-left: -0.89cm;
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
$sizeHeight =$item->logo ? 29 : 33;
$sizeWidth = $item->logo ? 0.97 : 0.91;
@endphp

<div class="{{ $loop->last ? '' : 'page-break' }}">
    <div class="{{  $item->logo ? 'image-container-logo' : 'image-container' }}">
        @if($item->logo)
        <span class="font-size-elitech">Elitech</span>
        @else
        <div class="logo"></div>
        @endif
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item->noseri, $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}" />
        <div class="text-center">{{ $item->noseri }}</div>
    </div>
</div>
@endforeach


{{-- LAMA --}}
{{-- @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $sizeHeight = $isLogo ? 29 : 33;
        $sizeWidth = $isLogo ? 0.97 : 0.91;
    @endphp --}}
    {{-- ulangi 5 kali --}}
    {{-- @foreach ($data as $item)
        <div class="{{ $loop->last ? '' : 'page-break' }}">
            <div class="{{ $isLogo ? 'image-container-logo' : 'image-container' }}">
                @if($isLogo)
                <span class="font-size-elitech">Elitech</span>
                @else
                <div class="logo"></div>
                @endif
                <img
                    src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item, $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}" />
                <div class="text-center">{{ $item }}</div>
            </div>
        </div>
    @endforeach --}}
</body>

</html>
