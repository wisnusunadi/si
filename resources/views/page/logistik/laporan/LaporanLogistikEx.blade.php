<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                @if($header == 'ekspedisi')
                Laporan Pengiriman Ekspedisi
                @foreach ($data as $d)
                @endforeach
                @elseif ($header == 'nonekspedisi')
                Laporan Pengiriman Non Ekspedisi
                @else
                Laporan Pengiriman Ekspedisi dan Non Ekspedisi
                @endif
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>No SJ</th>
            <th>Tanggal Kirim</th>
            <th>No Resi</th>
            <th>Customer</th>
            <th>Alamat</th>
            <th>Provinsi</th>
            <th>Jasa Ekspedisi</th>
            <th>Nama Produk</th>
            <th>Variasi</th>
            <th>Jumlah</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td style="text-align:left">{{$loop->iteration}}</td>
            <td style="text-align:left">
                @if(isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                @else
                {{$d->DetailPesananPart->Pesanan->so}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                @else
                {{$d->DetailPesananPart->Pesanan->no_po}}
                @endif
            </td>
            <td style="text-align:left">{{ $d->Logistik->nosurat}}</td>
            <td style="text-align:left">{{ $d->Logistik->tgl_kirim}}</td>
            <td style="text-align:left">
                @if($d->Logistik->noresi == "")
                -
                @else
                {{$d->Logistik->noresi}}
                @endif
            </td>
            <td style="text-align:left">
                <?php
                if (isset($d->DetailPesananProduk)) {
                    $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                    } else if ($name[1] == 'SPA') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                    }
                } else {
                    $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        echo $d->DetailPesananPart->Pesanan->Spa->Customer->nama;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananPart->Pesanan->Spb->Customer->nama;
                    }
                }
                ?>
            </td>
            <td style="text-align:left">
                <?php
                if (isset($d->DetailPesananProduk)) {
                    $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                    } else if ($name[1] == 'SPA') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                    }
                } else {
                    $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        echo $d->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                    }
                }
                ?>
            </td>
            <td style="text-align:left">
                <?php
                if (isset($d->DetailPesananProduk)) {
                    $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } else if ($name[1] == 'SPA') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                } else {
                    $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        echo $d->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                    } else if ($name[1] == 'SPB') {
                        echo $d->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                }
                ?>
            </td>
            <td style="text-align:left">
                @if (!empty($d->Logistik->ekspedisi_id))
                {{ $d->Logistik->Ekspedisi->nama}}
                @else
                {{ $d->Logistik->nama_pengirim}}
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->GudangBarangJadi->Produk->nama}}
                @else
                {{ $d->DetailPesananPart->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->DetailPesananProduk))
                @if ($d->DetailPesananProduk->GudangBarangJadi->nama != '')
                {{$d->DetailPesananProduk->GudangBarangJadi->nama}}
                @endif
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->NoseriDetailLogistik))
                {{ $d->NoseriDetailLogistik->count()}}
                @else
                {{ $d->DetailPesananPart->jumlah;}}
                @endif
            </td>
            <td style="text-align:left">
                {{ $d->Logistik->State->nama}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>