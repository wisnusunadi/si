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
        @foreach ($data as $index => $d )
        <tr>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;" >
                {{$index + 1}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->no_paket}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->Customer->nama}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->instansi}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->satuan}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->tgl_buat}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                {{$d->Ekatalog->tgl_kontrak}}
            </td>
            <td >
                @if($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                @else
                {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                @endif
            </td>
            <td >
                @if(count($d->DetailPesanan) > 0)

                @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach


                @endif

            </td>
            <td >
                {{ $d->DetailPesanan[0]->jumlah}}
            </td>
            <td >
                {{ $d->DetailPesanan[0]->harga}}
            </td>
            <td >
                {{ $d->DetailPesanan[0]->ongkir}}
            </td>
            <td >
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->Ekatalog->status}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>
        </tr>

        @for($i=1;$i<$d->DetailPesanan->count();$i++)
        <tr>
            <td>
                @if($d->DetailPesanan[$i]->PenjualanProduk->nama_alias != '')
                {{ $d->DetailPesanan[$i]->PenjualanProduk->nama_alias }}
                @else
                {{ $d->DetailPesanan[$i]->PenjualanProduk->nama }}
                @endif
            </td>
            <td >
                @if(count($d->DetailPesanan) > 0)

                @foreach($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach


                @endif

            </td>
            <td >
                {{ $d->DetailPesanan[$i]->jumlah}}
            </td>
            <td >
                {{ $d->DetailPesanan[$i]->harga}}
            </td>
            <td >
                {{ $d->DetailPesanan[$i]->ongkir}}
            </td>
            <td >
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
            </td>
        </tr>
        @endfor
        @endforeach
    </tbody>
</table>
