<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                {{$header}}
            </th>
        </tr>
        <tr>
            <th>No</th>

            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Produk</th>
            <th>Detail Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Ongkir</th>
            <th>Subtotal</th>
            <th>Status Penjualan</th>
            <th>Status AKN</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d )
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->no_paket}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->Customer->nama}}
                @elseif(isset($d->Pesanan->Spa))
                {{$d->Pesanan->Spa->Customer->nama}}
                @else
                {{$d->Pesanan->Spb->Customer->nama}}
                @endif
            </td>
            <td>
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->instansi}}
                @endif
            </td>
            <td>
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->satuan}}
                @endif
            </td>
            <td>
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_buat}}
                @endif
            </td>
            <td>
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_kontrak}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @if($d->PenjualanProduk->nama_alias != '')
                {{$d->PenjualanProduk->nama_alias}}
                @else
                {{$d->PenjualanProduk->nama}}
                @endif

                @else
                {{$d->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @foreach($d->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach

                @else
                {{$d->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                {{$d->jumlah}}
            </td>
            <td style="text-align:left">{{$d->harga}}</td>
            <td style="text-align:left">{{$d->ongkir}}</td>
            <td style="text-align:left">{{($d->jumlah * $d->harga) + $d->total}}</td>
            <td style="text-align:left">{{$d->pesanan->state->nama}}</td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->status}}
                @endif
            </td>
            <td style="text-align:left">{{$d->Pesanan->ket}}</td>
        </tr>

        @endforeach
    </tbody>
</table>
