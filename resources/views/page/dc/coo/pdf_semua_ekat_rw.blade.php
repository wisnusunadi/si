<head>
    <style>
        .page-break {
            page-break-after: always;
        }

        /* body {
            margin-left: 90px;
            margin-right: 90px;
            margin-bottom: 70px;
            margin-top: 70px;
        } */
        @page {
            margin: 0;

        }

        .mdtxt {
            font-size: 18px;

        }

        .nospace {
            width: 2%;
            white-space: nowrap;
        }

        .bold {
            font-weight: 600;
        }

        .vera {
            vertical-align: top;
        }

        .veramd {
            vertical-align: middle;
        }

        .wb {
            word-break: break-all;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .imgsize {
            width: auto;
            height: 35px;
        }

        div.header {
            position: fixed;
            height: 20px;
            margin-left: 84px;
            margin-right: 84px;
            margin-top: 65px;
        }

        div.body {
            position: fixed;
            height: auto;
            margin-left: 90px;
            margin-right: 90px;
            margin-bottom: 70px;
            margin-top: 120px;
        }

        div.footer {
            position: fixed;
            bottom: 80px;
            left: 0px;
            right: 0px;
        }

        .trheight {
            height: 40px;
        }

        .back {
            background-image: url("{{ public_path('assets/image/background_coo.jpg') }}");
            background-size: 100%;
        }

        .back {
            background-image: url("{{ public_path('assets/image/background_coo.jpg') }}");
            background-size: 100%;
        }

        .noback {
            background-image: url("{{ public_path('assets/image/no_background_coo.jpg') }}");
            background-size: 100%;
        }

        .table {
            width: 100%;
        }
    </style>
</head>


<body class="back">
    <?php
    $jumlah = 0;
    $len = count($data);
    ?>
    @foreach ($data as $d)
        <?php
        if ($jumlah == $len - 1) {
            echo '<div>';
        } else {
            echo ' <div class="page-break">';
        }
        ?>

        <div class="header">
            <table border="0" style="border-collapse: collapse; text-align:right;width:625px">
                <tbody>
                    <tr>
                        <td style="width:52.3%;"></td>
                        <td class="veramd align-center" style="width:57.7%;">
                            <h3>{{ $d['no_coo'] }} / SKA / {{ $d['romawi'] }} / SPA / {{ $d['tahun'] }}</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="body">
            <table style="text-align:center;" class="table">
                <tbody>
                    <tr>
                        <td>
                            <h1><u>CERTIFICATE OF ORIGIN</u></h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:10px"></td>
                    </tr>
                </tbody>
            </table>
            <div class="row mdtxt">
                <table class="table" style="border-collapse: collapse; margin-left: 30px;">
                    <tbody>
                        <tr>
                            <td colspan="3">Berdasarkan ijin edar/produksi dari <b>Kementrian Kesehatan Nomor:</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:5px"></td>
                        </tr>
                        <tr>
                            <td class="nospace vera"><b>KEMENKES RI ESUKA - FR.03.04/IVA/03435/2023</b></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:20px"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mdtxt">
                <div class="col-12">
                    <table border="0" style="width:100%" id="produktable">
                        <tbody>
                            <tr>
                                <td style="width:2%"></td>
                                <td colspan="3" class="nospace">PT SINKO PRIMA ALLOY (penyedia), menyerahkan hasil
                                    produksi:</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="height:10px"></td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheight">Nama Produk</td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    ELITECH ANTROPOMETRI KIT Type KIT-10
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheight">No Paket</td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    {{ $d['seri'] }}
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheightitem">Item Produk</td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    Digit-Pro Ida ({{ $d['item'][0]->noseri }})
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheightitem"></td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    Digit-Pro Baby ({{ $d['item'][1]->noseri }})
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheightitem"></td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    MTB-2MTR ({{ $d['item'][2]->noseri }})
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheightitem"></td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    MTR-Baby002 ({{ $d['item'][3]->noseri }})
                                </td>
                            </tr>
                            <tr class="vera bold">
                                <td style="width:2%"></td>
                                <td class="nospace trheightitem"></td>
                                <td class="nospace align-center"> : </td>
                                <td class="wb">
                                    PTB-2in1 ({{ $d['item'][4]->noseri }})
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mdtxt">
                <table border="0" class="table align-center" width="100%" style="border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="height:15px">
                            </td>
                        </tr>
                        <tr>
                            <td class="align-center">Kepada :</td>
                        </tr>
                        <tr>
                            <td style="height:3px"></td>
                        </tr>
                        <tr>
                            <td class="wb">
                                <b>
                                    {{ $d['kepada_prov'] }} <br> ({{ $d['kepada_kab'] }})
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mdtxt">
                <table border="0" class="table align-center" width="100%" style="border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="height:20px">
                            </td>
                        </tr>
                        <tr>
                            <td class="align-center">Untuk ID & Nama Paket :</td>
                        </tr>
                        <tr>
                            <td style="height:3px"></td>
                        </tr>
                        <tr>
                            <td class="wb"><b>
                                    {{$d['no_paket']}}
                                </b></td>
                        </tr>
                        <tr style="min-height: 20px; max-height: 65px;">
                            <td class="wb">
                                <b class="font-size:auto;">
                                    {{$d['deskripsi']}}
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mdtxt">
                <table border="0" class="table align-center" width="100%">
                    <tbody>
                        <tr>
                            <td style="height:20px"></td>
                        </tr>
                        <tr>
                            <td class="align-center"><i>Dalam kondisi baru, baik dan lengkap.</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <div class="row mdtxt">
                <table border="0" class="table align-center" width="100%">
                    <tbody>
                        <tr>
                            <td width="50%"></td>
                            <td class="align-center">Surabaya, {{$d['tgl']}}
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td class="align-center">PT. Sinko Prima Alloy</td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td class="align-center" style="height:75px;">
                                {{-- @if ($jenis == 'ttd')
                                @if (empty($d->nama) && $d->ket == 'spa')
                                    @if ($stamp == 1)
                                        <img src="{{ public_path('assets/image/spa_stamp.png') }}" width="200"
                                            height="100">
                                    @else
                                        <img src="{{ public_path('assets/image/spa.png') }}" width="200"
                                            height="100">
                                    @endif
                                @elseif(empty($d->nama) && $d->ket == 'emiindo')
                                    <img src="{{ public_path('assets/image/emiindo.png') }}" width="100"
                                        height="100">
                                @else
                                    <img src="" width="100" height="100">
                                @endif
                            @endif --}}
                                <img src="{{ public_path('assets/image/emiindo.png') }}" width="100"
                                    height="100">
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td class="align-center"><b><u>
                                        {{-- @if (empty($d->nama) && $d->ket == 'spa')
                                        Kusmardiana Rahayu
                                    @elseif(empty($d->nama) && $d->ket == 'emiindo')
                                        Bambang Hendro M BE
                                    @else
                                        {{ $d->nama }}
                                    @endif --}}
                                        Bambang Hendro M BE

                                    </u></b></td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td class="align-center">
                                {{-- @if (empty($d->nama) && $d->ket == 'spa')
                                Q.A Manager
                            @elseif(empty($d->nama) && $d->ket == 'emiindo')
                                Q.A Departement
                            @else
                                {{ $d->jabatan }}
                            @endif --}}
                                Q.A Departement
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>

        <?php $jumlah++; ?>
    @endforeach

</body>
