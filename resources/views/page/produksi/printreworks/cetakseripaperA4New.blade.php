<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Seri A4</title>
    <style>
        /* Styling opsional */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            vertical-align: top;
        },
        .font-size-elitech {
            font-size: 13pt;
        }
        .center {
  text-align: center;
}

        .font-size-nonelitech {
            font-size: 10px;
            text-align: center;
        }

        .text-left {
            margin-left: 0.1cm;
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


        .image-container-semua {
            margin-left: -0.2cm;
        }
    </style>
</head>

<body>
    <table border="0">
        @for ($i = 0; $i < count($data); $i += 5)
            <tr>
                @for ($j = $i; $j < $i + 5 && $j < count($data); $j++)
                    @php
                        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
                        $merkLogo = '';
                        $sizeHeight = $merkLogo != '' ? 29 : 15;
                        $sizeWidth = $merkLogo != '' ? 0.97 : 0.91;


                    @endphp
                    <td style="height: 20px; {{ $j === $i + 2 ? 'border-right: none;' : '' }}"  >
                        <div class="image-container-semua">
                            <br>
                            <div class="center" >
                            <img
                                src="data:image/png;base64,{{ base64_encode($generator->getBarcode('AD0001', $generator::TYPE_CODE_128_B, $sizeWidth, $sizeHeight)) }}"  />
                            </div>
                                <div class="font-size-nonelitech">{{$data[$j]}}</div>
                        </div>
                    </td>
                @endfor
                @if ($j % 5 !== 0)
                    @for ($k = $j % 5; $k < 5; $k++)
                        <td style="height: 20px; border-left: none;"></td>
                    @endfor
                @endif
            </tr>
        @endfor
    </table>
</body>
</html>
