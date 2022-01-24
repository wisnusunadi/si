<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                {{$header}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No PO</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Tanggal PO</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Produk</th>
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
        @foreach ($data as $d)
        <tr>
            <td style="text-align:left">{{ $loop->iteration }}</td>
            <td style="text-align:left">{{$d->Pesanan->no_po}}</td>
            <td style="text-align:left">
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
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_buat}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_kontrak}}
                @endif
            </td>
            <td style="text-align:left">
                {{$d->Pesanan->tgl_po}}
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->instansi}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->satuan}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))
                {{$d->PenjualanProduk->nama}}
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