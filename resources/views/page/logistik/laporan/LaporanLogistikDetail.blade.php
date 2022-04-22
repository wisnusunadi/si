<table style="border:1px solid #000">
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
            <th>Customer</th>
            <th>Alamat</th>
            <th>Provinsi</th>
            <th>No SJ</th>
            <th>Tanggal Kirim</th>
            <th>No Resi</th>
            <th>Jasa Ekspedisi</th>
            <th>Nama Produk</th>
            <th>Variasi</th>
            <th>Jumlah</th>
            <th>No Seri</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>
                @if($i->DetailPesananProduk)
                {{$i->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                @else
                {{$i->DetailPesananPart->Pesanan->so}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                {{$i->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                @else
                {{$i->DetailPesananPart->Pesanan->no_po}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                    @if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spa)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spb)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}
                    @endif
                @else
                    @if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->satuan}}
                    @elseif($i->DetailPesananPart->Pesanan->Spa)
                    {{$i->DetailPesananPart->Pesanan->Spa->Customer->nama}}
                    @elseif($i->DetailPesananPart->Pesanan->Spb)
                    {{$i->DetailPesananPart->Pesanan->Spb->Customer->nama}}
                    @endif
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                    @if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spa)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spb)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}
                    @endif
                @else
                    @if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->alamat}}
                    @elseif($i->DetailPesananPart->Pesanan->Spa)
                    {{$i->DetailPesananPart->Pesanan->Spa->Customer->alamat}}
                    @elseif($i->DetailPesananPart->Pesanan->Spb)
                    {{$i->DetailPesananPart->Pesanan->Spb->Customer->alamat}}
                    @endif
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                    @if($i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spa)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama}}
                    @elseif($i->DetailPesananProduk->DetailPesanan->Pesanan->Spb)
                    {{$i->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama}}
                    @endif
                @else
                    @if($i->DetailPesananPart->Pesanan->Ekatalog)
                    {{$i->DetailPesananPart->Pesanan->Ekatalog->Provinsi->nama}}
                    @elseif($i->DetailPesananPart->Pesanan->Spa)
                    {{$i->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama}}
                    @elseif($i->DetailPesananPart->Pesanan->Spb)
                    {{$i->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama}}
                    @endif
                @endif
            </td>
            <td>
                {{$i->Logistik->nosurat}}
            </td>
            <td>
                {{$i->Logistik->tgl_kirim}}
            </td>
            <td>
                {{$i->Logistik->no_resi}}
            </td>
            <td>
                @if($i->Logistik->Ekspedisi)
                {{$i->Logistik->Ekspedisi->nama}}
                @else
                {{$i->Logistik->nama_pengirim}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                    @if($i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                    {{$i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                    @else
                    {{$i->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                    @endif
                @else
                    {{$i->DetailPesananPart->Sparepart->nama}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                {{$i->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$i->DetailPesananProduk->GudangBarangJadi->nama}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                {{$i->NoseriDetailLogistik->count()}}
                @else
                {{$i->DetailPesananPart->jumlah}}
                @endif
            </td>
            <td>
                @if($i->DetailPesananProduk)
                @if($i->NoseriDetailLogistik)
                    @foreach ($i->NoseriDetailLogistik as $i)
                        {{ $loop->first ? '' : ', ' }}
                        {{ $i->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                    @endforeach
                @endif
                @endif
            </td>
            <td>
                @if(isset($i->Logistik->State))
                {{$i->Logistik->State->nama}}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
