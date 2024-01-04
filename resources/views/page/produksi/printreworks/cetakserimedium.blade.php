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
            margin-top: 0.0001cm;
            margin-left: 0.2cm;
            font-size: 12pt;
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
        <div class="{{ $loop->last ? '' : 'page-break' }}">
            <div class="image-container">
                <span class="font-size-elitech">Elitech</span><br>
                <img
                    src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item, $generator::TYPE_CODE_128_B, 0.97, 29)) }}" />
                <div class="text-center">{{ $item }}</div>
            </div>
        </div>
    @endforeach
</body>

</html>
