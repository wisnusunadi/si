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
            bottom: 100px;
            left: 0px;
            right: 0px;
        }

        .trheight {
            height: 40px;
        }

        body {
            background-image: url("{{public_path('assets/image/background_coo.jpg') }}");
            background-size: 100%;
        }
    </style>
</head>


<body>
    <?php
    if ($count > 1) {
        echo ' <div class="page-break">';
    } else {
        echo '<div>';
    }
    ?>
    @foreach($data as $d)
    <div class="header">
        <table border="0" style="border-collapse: collapse; text-align:right;width:625px" class="table">
            <tbody>
                <tr>
                    <td style="width:52.3%;"></td>
                    <td class="veramd align-center" style="width:47.7%;">
                        <h3> {{$d->id}}/ SKA / {{App\Http\Controllers\DcController::bulan_romawi($d->Noserilogistik->DetailLogistik->logistik->tgl_kirim)}} / SPA / {{App\Http\Controllers\DcController::tahun($d->Noserilogistik->DetailLogistik->logistik->tgl_kirim)}} </h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="body">
        <table border="0" style="border-collapse: collapse; text-align:center;" class="table" width="100%">
            <tbody>
                <tr>
                    <td style="height: auto;">
                        <h1><u>CERTIFICATE OF ORIGIN</u></h1>
                    </td>
                </tr>
                <tr>
                    <td style="height:10px"></td>
                </tr>
            </tbody>
        </table>
        <div class="row mdtxt">
            <table border="0" class="table" style="border-collapse: collapse;" width="100%">
                <tbody>
                    <tr>
                        <td colspan="3" class="align-center">Berdasarkan ijin edar/produksi dari</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="height:5px"></td>
                    </tr>
                    <tr>
                        <td class="nospace vera"><b>Kementrian Kesehatan Nomor</b></td>
                        <td class="nospace vera"><b>:</b></td>
                        <td class="wb vera"><b>KEMENKES RI AKD
                                @if ($d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd != '')
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd}}
                                @endif
                            </b></td>
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
                            <td colspan="3" class="nospace">PT SINKO PRIMA ALLOY (penyedia), menyerahkan hasil produksi:</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="height:10px"></td>
                        </tr>
                        <tr class="vera bold">
                            <td style="width:2%"></td>
                            <td class="nospace trheight">Nama Produk</td>
                            <td class="nospace align-center"> : </td>
                            <td class="wb">
                                @if ($d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo != '')
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo}}
                                @endif
                            </td>
                        </tr>
                        <tr class="vera bold">
                            <td style="width:2%"></td>
                            <td class="nospace trheight">Tipe</td>
                            <td class="nospace align-center"> : </td>
                            <td class="wb">
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama}}
                            </td>
                        </tr>
                        <tr class="vera bold">
                            <td style="width:2%"></td>
                            <td class="nospace trheight">Nomor Seri</td>
                            <td class="nospace align-center"> : </td>
                            <td class="wb">
                                {{$d->Noserilogistik->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri}}
                            </td>
                        </tr>
                        <tr class="vera bold">
                            <td style="width:2%"></td>
                            <td class="nospace trheight">Merk Produk</td>
                            <td class="nospace align-center"> : </td>
                            <td class="wb">
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->merk}}
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
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi}}
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
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket}}
                            </b></td>
                    </tr>
                    <tr style="min-height: 20px; max-height: 65px;">
                        <td class="wb">
                            <b class="font-size:auto;">
                                {{$d->Noserilogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->deskripsi}}
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
                        <td class="align-center">Surabaya, {{App\Http\Controllers\DcController::tgl_footer($d->Noserilogistik->DetailLogistik->logistik->tgl_kirim)}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td class="align-center">Dummy Lorem Ipsum</td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td class="align-center" style="height:75px;"></td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td class="align-center"><b><u>
                                    @if(empty($d->nama) && $d->ket == 'spa')
                                    Kusmardiana Rahayu
                                    @elseif(empty($d->nama) && $d->ket == 'emiindo')
                                    Bambang Hendro M BE
                                    @else
                                    {{$d->nama}}
                                    @endif
                                </u></b></td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td class="align-center">
                            @if(empty($d->nama) && $d->ket == 'spa')
                            Q.A Manager
                            @elseif(empty($d->nama) && $d->ket == 'emiindo')
                            Q.A Departement
                            @else
                            {{$d->jabatan}}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    @endforeach
</body>
