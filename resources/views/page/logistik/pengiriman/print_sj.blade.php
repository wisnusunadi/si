<style>
    .mdtxt {
        font-size: 15px;
    }

    .nospace {
        width: 5%;
        white-space: nowrap;
    }

    .margin {
        margin-left: 10px;
        margin-right: 10px;
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
        height: auto;
        top: -50px;
        left: 0px;
        right: 0px;
    }

    div.body {
        position: fixed;
        height: auto;
        top: 30px;
        left: 0px;
        right: 0px;
    }

    div.footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: auto;
    }
</style>
<div class="header">
    <table border="0" style="border-collapse: collapse; text-align:center;" class="table" width="100%">
        <tbody style="border-bottom: 1px solid;">
            <tr>
                <td>
                    <h2>SURAT JALAN</h2>
                </td>
                <td class="align-right">
                    <img src="{{public_path('assets/image/logo/spa_long.png') }}" alt="" class="imgsize">
                </td>
            </tr>
        </tbody>
    </table>
</div>
@foreach($data as $d)
<div class="body">
    <div class="row mdtxt">
        <div class="col-12">
            <table style="width:100%">
                <tbody>

                    <tr>
                        <td colspan="4">Pengirim</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="wb">PT. Sinko Prima Alloy</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="wb">Pergudangan Osowilangun Permai Blok E7-E10</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="wb">Surabaya, Jawa Timur</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="wb">08119092890</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="4">Kepada Yth</td>

                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="4" class="wb">
                            <?php
                            $name = explode('/', $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);

                            if ($name[1] == 'EKAT') {
                                echo    $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                            } else if ($name[1] == 'SPA') {
                                echo   $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                            } else {
                                echo $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                            }

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="4" class="wb">
                            <?php
                            $name = explode('/', $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);

                            if ($name[1] == 'EKAT') {
                                echo    $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
                            } else if ($name[1] == 'SPA') {
                                echo   $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                            } else {
                                echo $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                            }

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="4" class="wb">
                            <?php
                            $name = explode('/', $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);

                            if ($name[1] == 'EKAT') {
                                echo    $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->Provinsi->nama;
                            } else if ($name[1] == 'SPA') {
                                echo   $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                            } else {
                                echo $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                            }

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="4" class="wb">
                            <?php
                            $name = explode('/', $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);

                            if ($name[1] == 'EKAT') {
                                echo    $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
                            } else if ($name[1] == 'SPA') {
                                echo   $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
                            } else {
                                echo $d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
                            }

                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <table border=0 class="table" width="100%">
            <tbody>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-12 align-left" style="border-bottom: 1px solid;">
            <h4>{{$d->nosurat}}</h4>
        </div>
    </div><br>
    <div class="row mdtxt">
        <div class="col-12">
            <table class="table" border="0" style="border-collapse: collapse; text-align:center; " width="100%">
                <thead class="align-center">
                    <tr>
                        <th class="nospace">No SO</th>
                        <th class="nospace">No PO</th>
                        <th class="nospace">No Invoice</th>
                        <th>Tanggal Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="nospace">{{$d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so}}</td>
                        <td class="nospace">{{$d->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}</td>
                        <td class="wb">-</td>
                        <td class="nospace">
                            {{App\Http\Controllers\LogistikController::tgl_footer($d->tgl_kirim)}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12 align-left">
            <h4>Produk</h4>
        </div>
    </div>
    <div class="row mdtxt">
        <div class="col-12">
            <table class="table" border="0" style="border-collapse: collapse; text-align:center;" width="100%">
                <thead class="align-center" style="border-bottom: 1px solid black;">
                    <tr>
                        <th>Nama Barang</th>
                        <th class="nospace">Jumlah</th>
                        <th>No Seri</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_produk as $e)
                    <tr>
                        <td class="wb align-left">
                            @if($e->DetailPesananProduk->GudangBarangJadi->nama == '')
                            {{$e->DetailPesananProduk->GudangBarangJadi->produk->nama}}
                            @else
                            {{$e->DetailPesananProduk->GudangBarangJadi->nama}}
                            @endif
                        </td>
                        <td class="nospace align-right">{{$e->NoseriDetailLogistik->count()}} pcs</td>
                        <td class="wb">
                            @foreach($e->NoseriDetailLogistik as $x)
                            {{$x->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri}}
                            @if( !$loop->last)
                            ,
                            @endif
                            @endforeach
                        </td>
                        <td class="wb">-</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row mdtxt">
        <div class="col-12">
            <table class="table" border="0" style="border-collapse: collapse;" width="100%">
                <tbody>
                    <tr>
                        <td class="nospace">Ekspedisi </td>
                        <td class="nospace"> : </td>
                        <td class="wb align-left">
                            @if ($d->nama_pengirim == '')
                            {{$d->Ekspedisi->nama}}
                            @else
                            {{$d->nama_pengirim}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="nospace">Total Ongkir </td>
                        <td class="nospace"> : </td>
                        <td class="wb align-left">-</td>
                    </tr>
                    <tr>
                        <td class="nospace">Catatan </td>
                        <td class="nospace"> : </td>
                        <td class="wb align-left">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="footer">
    <table border=0 class="table align-center" width="100%">
        <thead>
            <tr>
                <th>Diterima Oleh,</th>
                <th>Dibawa Oleh,</th>
                <th>Dibuat Oleh,</th>
            </tr>
        </thead>
        <tbody style="border-bottom: 1px solid black;">
            <tr style="border-bottom: 1px solid;">
                <td style="height:40px;"></td>
                <td style="height:40px;"></td>
                <td style="height:40px;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td>_____________</td>
                <td>_____________</td>
                <td>_____________</td>
            </tr>
            <tr>
                <td></td>
                <td>@if ($d->nama_pengirim == '')
                    {{$d->Ekspedisi->nama}}
                    @else
                    {{$d->nama_pengirim}}
                    @endif
                </td>
                <td>Erna Cantika A</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endforeach
    <div class="align-right">No Dokumen: SPA-FR/GUD-04, Tanggal Terbit: 20 Maret 2020, Revisi:02</div>
</div>