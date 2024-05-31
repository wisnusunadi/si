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
            /* margin-left: 0.4cm; */
            margin-bottom: 0cm;
            font-size: 7pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        .margin-left-11 {
            margin-left: -0.4cm;
        }

        .margin-left-12 {
            margin-left: -0.5cm;
        }

        .margin-left-13 {
            margin-left: -0.6cm;
        }

        .margin-left-14 {
            margin-left: -0.55cm;
        }

        .small-text {
            margin-left: 0.9cm;
        }

        .small-text-custom {
            margin-left: 0.1cm;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    {{-- ulangi 5 kali --}}
    @foreach ($data as $item)
        @php
            $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

            $classPage = '';

            switch (strlen($item['noseri'])) {
                case 11:
                    $classPage = 'margin-left-11';
                    break;
                case 12:
                    $classPage = 'margin-left-12';
                    break;
                case 13:
                    $classPage = 'margin-left-13';
                    break;
                default:
                    $classPage = 'margin-left-14';
            }
        @endphp

        {{-- when last page not page break --}}
        <div class="{{ $loop->last ? '' : 'page-break' }}">
            @if ($item['id'] == 11)
                <div class="{{ $classPage }}">
                    <img
                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item['noseri'], $generator::TYPE_CODE_93, 0.5, 16)) }}" />
                    <div class="small-text-custom">{{ $item['noseri'] }}</div>
                </div>
            @else
                <div class="{{ $classPage }}">
                    <img
                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item['noseri'], $generator::TYPE_CODE_93, 0.86, 16)) }}" />
                    <div class="small-text">{{ $item['noseri'] }}</div>
                </div>
            @endif

        </div>
    @endforeach
</body>

</html>
