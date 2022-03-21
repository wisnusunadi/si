<style>
.va {
  vertical-align: bottom;
}
</style>
@if($seri == 'kosong')
<table border="1">
    <thead>
        <tr>
            <th colspan="2"  style="text-align:center">
                {{$header}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan</th>
            <th>Tgl Surat Jalan</th>
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
        @foreach ($data as $index => $d)
        <?php
        $cPrd = $d->DetailPesanan->count();
        $cPart = $d->DetailPesananPart->count();
        $tot = $cPrd + $cPart;
        ?>
        <tr>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$index + 1}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->so}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->no_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->tgl_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(count($d->DetailPesanan) > 0)
                    @if($d->DetailPesanan[0]->getJumlahProduk() > 1)

                    @foreach($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                    @foreach( $p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @else

                    @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                    @foreach( $p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @endif
            @else
                @if(isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat}}
                @endif
            @endif

            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(count($d->DetailPesanan) > 0)
                @if($d->DetailPesanan[0]->getJumlahProduk() > 1)

                @foreach($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @else

                @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @endif
        @else
            @if(isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
            {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim}}
            @endif
        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->no_paket}}
                        @endif
            </td>


            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->Customer->nama}}
                        @elseif(isset($d->Spa))
                        {{$d->Spa->Customer->nama}}
                        @elseif(isset($d->Spb))
                        {{$d->Spb->Customer->nama}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->instansi}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->satuan}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->tgl_buat}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->tgl_kontrak}}
                        @endif
            </td>

            <td>
                @if(count($d->DetailPesanan) > 0)

                    @if($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                    @endif

                @else
                {{ $d->DetailPesananPart[0]->Sparepart->nama}}
                @endif
            </td>
            <td>
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
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga}}
                @else
                {{ $d->DetailPesananPart[0]->harga}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->ongkir}}
                @else
                {{ $d->DetailPesananPart[0]->ongkir}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->harga *  $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(isset($d->Ekatalog))
                {{$d->Ekatalog->status}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>

        </tr>

        {{-- Rowspan --}}
        @if(count($d->DetailPesanan) > 0)
             @for($i=1;$i<$d->DetailPesanan->count();$i++)
             <tr>
                <td>
                    @if($d->DetailPesanan[$i]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama }}
                    @endif
                </td>
                <td>
                    @foreach($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->jumlah}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->ongkir}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga *  $d->DetailPesanan[$i]->jumlah }}
                </td>
             </tr>
             @endfor
        @else
            @for($i=1;$i<$d->DetailPesananPart->count();$i++)
            <tr>
              <td> {{ $d->DetailPesananPart[$i]->Sparepart->nama }}</td>
              <td></td>
              <td>
                {{ $d->DetailPesananPart[$i]->jumlah}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->ongkir}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga *  $d->DetailPesananPart[$i]->jumlah }}
            </td>
            </tr>
            @endfor
        @endif
        @endforeach
    </tbody>
@else
<table border="1">
    <thead>
        <tr>
            <th colspan="4"  style="text-align:center">
                {{$header}} + No Seri
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan</th>
            <th>Tgl Surat Jalan</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Produk</th>
            <th>Detail Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Ongkir</th>
            <th>Subtotal</th>
            <th>Status Penjualan</th>
            <th>Status AKN</th>
            <th>Keterangan</th>
            <th>Seri</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $d)
        <?php
        $cPrd = $d->DetailPesanan->count();
        $cPart = $d->DetailPesananPart->count();
        $tot = $cPrd + $cPart;
        ?>
        <tr>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$index + 1}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->no_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->tgl_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(count($d->DetailPesanan) > 0)
                    @if($d->DetailPesanan[0]->getJumlahProduk() > 1)

                    @foreach($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                    @foreach( $p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @else

                    @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                    @foreach( $p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @endif
            @else
                @if(isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat}}
                @endif
            @endif

            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(count($d->DetailPesanan) > 0)
                @if($d->DetailPesanan[0]->getJumlahProduk() > 1)

                @foreach($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @else

                @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @endif
        @else
            @if(isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
            {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim}}
            @endif
        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->no_paket}}
                        @endif
            </td>


            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if(isset($d->Ekatalog))
                        {{$d->Ekatalog->Customer->nama}}
                        @elseif(isset($d->Spa))
                        {{$d->Spa->Customer->nama}}
                        @elseif(isset($d->Spb))
                        {{$d->Spb->Customer->nama}}
                        @endif
            </td>




            <td>
                @if(count($d->DetailPesanan) > 0)

                    @if($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                    @endif

                @else
                {{ $d->DetailPesananPart[0]->Sparepart->nama}}
                @endif
            </td>
            <td>
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
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga}}
                @else
                {{ $d->DetailPesananPart[0]->harga}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->ongkir}}
                @else
                {{ $d->DetailPesananPart[0]->ongkir}}
                @endif
            </td>
            <td>
                @if(count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->harga *  $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if(isset($d->Ekatalog))
                {{$d->Ekatalog->status}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>
            <td  style="vertical-align: center;">
                @if(count($d->DetailPesanan) > 0)
                @foreach($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach ( $p->NoseriDetailPesanan as $n )
                    {{$n->NoseriTGbj->NoseriBarangJadi->noseri}}
                        @if( !$loop->last)
            ,
            @endif
                @endforeach
                @if( !$loop->last)
                ,
                @endif
            @endforeach
            @endif
            </td>

        </tr>

        {{-- Rowspan --}}
        @if(count($d->DetailPesanan) > 0)
             @for($i=1;$i<$d->DetailPesanan->count();$i++)
             <tr>
                <td>
                    @if($d->DetailPesanan[$i]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama }}
                    @endif
                </td>
                <td>
                    @foreach($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->jumlah}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->ongkir}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga *  $d->DetailPesanan[$i]->jumlah }}
                </td>
                <td>

                    @foreach($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                    @foreach ( $p->NoseriDetailPesanan as $n )
                        {{$n->NoseriTGbj->NoseriBarangJadi->noseri}}
                            @if( !$loop->last)
                ,
                @endif
                    @endforeach
                    @if( !$loop->last)
                    ,
                    @endif
                @endforeach

                </td>
             </tr>
             @endfor
        @else
            @for($i=1;$i<$d->DetailPesananPart->count();$i++)
            <tr>
              <td> {{ $d->DetailPesananPart[$i]->Sparepart->nama }}</td>
              <td></td>
              <td>
                {{ $d->DetailPesananPart[$i]->jumlah}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->ongkir}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga *  $d->DetailPesananPart[$i]->jumlah }}
            </td>
              <td>

            </td>
            </tr>
            @endfor
        @endif
        @endforeach
    </tbody>
@endif


