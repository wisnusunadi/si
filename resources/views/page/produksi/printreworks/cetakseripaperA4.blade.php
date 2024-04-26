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

        */
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


                    @endphp
                    <td style="height: 100px; {{ $j === $i + 2 ? 'border-right: none;' : '' }}">
                        <span class="{{ $merkLogo != '' ? 'font-size-elitech' : 'logo' }}">{{ $merkLogo }}</span>
                        <img
                            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($data[$j]->noseri, $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}" />
                        <div class="text-center">{{ $data[$j]->noseri }}</div>
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
