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
            <th>No Urut</th>
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
        @foreach ($data as $index => $d)
        <?php $countsoprd = 0; $countsopart = 0;
        if(isset($d->DetailPesanan))
        {
            $countsoprd = $d->DetailPesanan->count();
        }
        if(isset($d->DetailPesananPart))
        {
            $countsopart = $d->DetailPesananPart->count();
        }
        $rowspan = $countsoprd + $countsopart;
        ?>
        <?php $countprd = 0; ?>
        <tr>
            <td rowspan="{{$rowspan}}">{{$loop->iteration}}</td>
            <td rowspan="{{$rowspan}}">
            @if($d->Ekatalog)
            {{$d->Ekatalog->no_paket}}
            @else
            -
            @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog)
                {{$d->Ekatalog->no_urut}}
                @else
                -
                @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog)
                {{$d->Ekatalog->Customer->nama}}
                @elseif($d->Spa)
                {{$d->Spa->Customer->nama}}
                @elseif($d->Spb)
                {{$d->Spb->Customer->nama}}
                @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog)
                {{$d->Ekatalog->instansi}}
                @else
                -
                @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog)
                {{$d->Ekatalog->satuan}}
                @else
                -
                @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog)
                {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_buat)) }}
                @else
                -
                @endif
            </td>
            <td rowspan="{{$rowspan}}">
                @if($d->Ekatalog->tgl_kontrak != '')
                {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_kontrak)) }}
                @else
                -
                @endif
            </td>

            @if(isset($d->DetailPesanan))
                @foreach ($d->DetailPesanan as $e)
                @if($countprd <= 0)
                <td>
                    @if($e->PenjualanProduk->nama_alias != '')
                    {{$e->PenjualanProduk->nama_alias}}
                    @else
                    {{$e->PenjualanProduk->nama}}
                    @endif
                </td>
                <td>
                    @foreach($e->DetailPesananProduk as $p)
                    {{ $p->gudangbarangjadi->produk->nama}}

                    @if ($p->gudangbarangjadi->nama != '')
                    {{ $p->gudangbarangjadi->nama}}
                    @endif

                    @if( !$loop->last)
                    ,
                    @endif

                    @endforeach
                </td>
                <td>{{$e->jumlah}}</td>
                <td>{{$e->harga}}</td>
                <td>{{$e->ongkir}}</td>
                <td>{{($e->jumlah * $e->harga) + $e->ongkir}}</td>
                <td rowspan="{{$rowspan}}">
                    {{$d->State->nama}}
                </td>
                <td >
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->status}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$rowspan}}">
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->ket}}
                    @elseif($d->Spa)
                    {{$d->Spa->ket}}
                    @elseif($d->Spb)
                    {{$d->Spb->ket}}
                    @endif</td>
                @else
                <tr>
                    <td> @if($e->PenjualanProduk->nama_alias != '')
                        {{$e->PenjualanProduk->nama_alias}}
                        @else
                        {{$e->PenjualanProduk->nama}}
                        @endif</td>
                    <td>
                        @foreach($e->DetailPesananProduk as $p)
                        {{ $p->gudangbarangjadi->produk->nama}}

                        @if ($p->gudangbarangjadi->nama != '')
                        {{ $p->gudangbarangjadi->nama}}
                        @endif

                        @if( !$loop->last)
                        ,
                        @endif

                        @endforeach
                    </td>
                    <td>{{$e->jumlah}}</td>
                    <td>{{$e->harga}}</td>
                    <td>{{$e->ongkir}}</td>
                    <td>{{($e->jumlah * $e->harga) + $e->ongkir}}</td>
                    <td >
                        @if($d->Ekatalog)
                        {{$d->Ekatalog->status}}
                        @else
                        -
                        @endif
                    </td>

                @endif
            </tr>
                <?php $countprd++ ?>
                @endforeach
            @endif
            @if(isset($d->DetailPesananPart))
                @foreach ($d->DetailPesananPart as $e)
                @if($countprd <= 0)
                <td>-</td>
                <td>{{$e->Sparepart->nama}}</td>
                <td>{{$e->jumlah}}</td>
                <td>{{$e->harga}}</td>
                <td>{{$e->ongkir}}</td>
                <td>{{($e->jumlah * $e->harga) + $e->ongkir}}</td>
                <td rowspan="{{$rowspan}}">
                    {{$d->State->nama}}
                </td>
                <td >
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->status}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$rowspan}}">
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->ket}}
                    @elseif($d->Spa)
                    {{$d->Spa->ket}}
                    @elseif($d->Spb)
                    {{$d->Spb->ket}}
                    @endif</td>
                @else
                <tr>
                    <td>-</td>
                    <td>{{$e->Sparepart->nama}}</td>
                    <td>{{$e->jumlah}}</td>
                    <td>{{$e->harga}}</td>
                    <td>{{$e->ongkir}}</td>
                    <td>{{($e->jumlah * $e->harga) + $e->ongkir}}</td>
                    <td>{{$d->Ekatalog->status}}</td>

                @endif
            </tr>
                <?php $countprd++ ?>
                @endforeach
            @endif

            @if(!isset($d->DetailPesanan) && !isset($d->DetailPesananPart))
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">-</td>
                <td rowspan="{{$rowspan}}">
                    {{$d->State->nama}}
                </td>
                <td >
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->status}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$rowspan}}">
                    @if($d->Ekatalog)
                    {{$d->Ekatalog->ket}}
                    @elseif($d->Spa)
                    {{$d->Spa->ket}}
                    @elseif($d->Spb)
                    {{$d->Spb->ket}}
                    @endif</td>
            @endif
            {{-- <td rowspan="{{$rowspan}}"></td>
            <td rowspan="{{$rowspan}}"></td>
            <td rowspan="{{$rowspan}}"></td>
            <td rowspan="{{$rowspan}}"></td>
            <td rowspan="{{$rowspan}}"></td>
            <td rowspan="{{$rowspan}}"></td> --}}


        @endforeach
        {{--@foreach ($data as $index => $d )
        <tr>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;" >
                {{$index + 1}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->no_paket}}
                @endif
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->Customer->nama}}
                @endif
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->instansi}}
                @endif
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->satuan}}
                @endif
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->tgl_buat}}
                @endif
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}"  style="vertical-align: center;">
                @if($d->Ekatalog)
                {{$d->Ekatalog->tgl_kontrak}}
                @endif
            </td>
            <td >
                {{-- @if($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                @else
                {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                @endif --}}
             {{-- </td>
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
                {{-- {{ $d->DetailPesanan[0]->jumlah}} --}}
            {{-- </td>
            <td >
                {{-- {{ $d->DetailPesanan[0]->harga}} --}}
            {{-- </td> --}}
            {{-- <td > --}}
                {{-- {{ $d->DetailPesanan[0]->ongkir}} --}}
            {{-- </td>
            <td > --}}
                {{-- {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}} --}}
            {{-- </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->Ekatalog->status}}
            </td>
            <td rowspan="{{$d->DetailPesanan->count()}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>
        </tr> --}}

        {{-- @for($i=1;$i<$d->DetailPesanan->count();$i++)
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
            <td > --}}
                {{-- {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}} --}}
            {{-- </td>
        </tr>
        @endfor
        @endforeach --}}
    {{-- </tbody> --}}
</table>
