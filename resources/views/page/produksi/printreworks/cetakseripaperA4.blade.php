<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Tabel DOMPDF</title>
    <style>
        /* Styling opsional */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
        }

        ,
        .font-size-elitech {
            font-size: 13pt;
        }

        .text-center {
            margin-left: 0.8cm;
        }

        .logo {
            margin-top: 0.2cm;
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

        }

        .image-container-14 {
            margin-left: -1cm;
        }
    </style>
</head>

<body>
    <table>
        @for ($i = 0; $i < count($data); $i += 3)
            <tr>
                @for ($j = $i; $j < $i + 3 && $j < count($data); $j++)
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
                        $sizeWidth = $merkLogo != '' ? 0.97 : 0.91;

                        $classLogo = '';

                        switch (strlen($data[$j]->noseri)) {
                            case 11:
                                $classLogo = 'image-container-logo-11';
                                break;
                            case 12:
                                $classLogo = 'image-container-logo-12';
                                break;
                            case 13:
                                $classLogo = 'image-container-logo-13';
                                break;
                            default:
                                $classLogo = 'image-container-logo-14';
                                break;
                        }

                        $classNotLogo = '';

                        switch (strlen($data[$j]->noseri)) {
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
                    <td style="height: 100px; {{ $j === $i + 2 ? 'border-right: none;' : '' }}">
                        <div class="{{ $merkLogo != '' ? $classLogo : $classNotLogo }}">
                            <span class="{{ $merkLogo != '' ? 'font-size-elitech' : 'logo' }}">{{ $merkLogo }}</span>
                            <img
                                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($data[$j]->noseri, $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}" />
                            <div class="text-center">{{ $data[$j]->noseri }}</div>
                        </div>
                    </td>
                @endfor

                @if ($j % 3 !== 0)
                    @for ($k = $j % 3; $k < 3; $k++)
                        <td style="height: 100px; border-left: none;"></td>
                    @endfor
                @endif
            </tr>
        @endfor
    </table>
</body>

</html>
