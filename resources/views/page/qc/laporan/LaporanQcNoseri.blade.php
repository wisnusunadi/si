<table style="border:1px solid #000">
    <thead>
        <tr>
            <th colspan="14" style="text-align:center">
                Laporan Pengiriman {{ucfirst($jenis)}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No AKN</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Customer</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Alamat</th>
            @if($jenis == "produk")
            <th>Nama Produk</th>
            <th>Produk</th>
            <th>No Seri</th>
            <th>Tanggal Uji</th>
            <th>Hasil</th>
            @elseif($jenis == "part")
            <th>Nama Part</th>
            <th>Tanggal Uji</th>
            <th>Jumlah OK</th>
            <th>Jumlah NOK</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if($jenis == "produk")
        @foreach ($data as $i)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$i->DetailPesananProduk->DetailPesanan->Pesanan->so}}</td>
                <td>
                    @if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket}}
                    @else
                    -
                    @endif
                </td>
                <td>{{$i->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}</td>
                <td>{{$i->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po}}</td>
                <td>@if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spa)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spb)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}
                    @endif
                </td>
                <td>@if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi}}
                    @else
                    -
                    @endif
                </td>
                <td>@if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spa)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spb)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}
                    @endif
                </td>
                <td>
                    @if($i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                    {{$i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                    @else
                    {{$i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                    @endif
                </td>
                <td>
                    {{$i->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$i->DetailPesananProduk->GudangBarangJadi->nama}}
                </td>
                <td>{{$i->NoseriTGbj->NoseriBarangJadi->noseri}}</td>
                <td>{{$i->tgl_uji}}</td>
                <td>{{$i->status}}</td>
            </tr>
        @endforeach
        @elseif($jenis == "part")
        @foreach ($data as $i)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$i->DetailPesananPart->Pesanan->so}}</td>
                <td>
                    @if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->no_paket}}
                    @else
                    -
                    @endif
                </td>
                <td>{{$i->DetailPesananPart->Pesanan->no_po}}</td>
                <td>{{$i->DetailPesananPart->Pesanan->tgl_po}}</td>
                <td>@if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->Customer->nama}}
                    @elseif($i->DetailPesananPart->Pesanan->Spa)
                    {{$i->DetailPesananPart->Pesanan->Spa->Customer->nama}}
                    @elseif($i->DetailPesananPart->Pesanan->Spb)
                    {{$i->DetailPesananPart->Pesanan->Spb->Customer->nama}}
                    @endif
                </td>
                <td>@if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->instansi}}
                    @else
                    -
                    @endif
                </td>
                <td>@if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->satuan}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->alamat}}
                    @elseif($i->DetailPesananPart->Pesanan->Spa)
                    {{$i->DetailPesananPart->Pesanan->Spa->Customer->alamat}}
                    @elseif($i->DetailPesananPart->Pesanan->Spb)
                    {{$i->DetailPesananPart->Pesanan->Spb->Customer->alamat}}
                    @endif
                </td>
                <td>
                    @if($i->DetailPesananPart->PenjualanProduk->nama_alias != '')
                    {{$i->DetailPesananPart->PenjualanProduk->nama_alias}}
                    @else
                    {{$i->DetailPesananPart->PenjualanProduk->nama}}
                    @endif
                </td>
                <td>
                    {{$i->DetailPesananPart->Sparepart->nama}}
                </td>
                <td>{{$i->tanggal_uji}}</td>
                <td>{{$i->jumlah_ok}}</td>
                <td>{{$i->jumlah_nok}}</td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>
