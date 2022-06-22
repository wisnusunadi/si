<style>
.va {
  vertical-align: bottom;
}
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="22" style="text-align:center">
              f
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan (Tgl Surat Jalan)</th>
            <th>No Urut</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Produk</th>
            <th>No Seri</th>
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
        @php
            $no = 1;
        @endphp
        @foreach ($data as  $d)

        <tr>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$no++}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->so}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->no_po}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->tgl_po}}</td>
        <td>
            @if ($d->getSuratJalanProduk($d->DetailPesananUnique()[0]->penjualan_produk_id)->count() > 0)
            @foreach($d->getSuratJalanProduk($d->DetailPesananUnique()[0]->penjualan_produk_id) as $f)
            {{ $f->Logistik->nosurat }}({{ date('d-m-Y', strtotime($f->Logistik->tgl_kirim)) }})
            @if( !$loop->last)
            ,
            @endif
            @endforeach
        @else
        -
        @endif
        </td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->Ekatalog->no_urut}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->Ekatalog->no_paket}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->Ekatalog->Customer->nama}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->Ekatalog->instansi}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">{{$d->Ekatalog->satuan}}</td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">
            {{date('d-m-Y', strtotime($d->Ekatalog->tgl_buat))}}
        </td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">
            {{date('d-m-Y', strtotime($d->Ekatalog->tgl_kontrak))}}
        </td>
        <td>
            @if($d->DetailPesananUnique()[0]->PenjualanProduk->nama_alias != '')
                {{$d->DetailPesananUnique()[0]->PenjualanProduk->nama_alias}}
            @else
                {{$d->DetailPesananUnique()[0]->PenjualanProduk->nama}}
            @endif
        </td>
        <td>
            @if ($d->getNoseri($d->DetailPesananUnique()[0]->penjualan_produk_id)->count() > 0)
            @foreach($d->getNoseri($d->DetailPesananUnique()[0]->penjualan_produk_id) as $g)
            {{ $g->NoseriTGbj->seri->noseri}}
            @if( !$loop->last)
            ,
            @endif
            @endforeach
            @else
            -
            @endif
        </td>
        <td>
                {{$d->getJumlahPesananUnique($d->DetailPesananUnique()[0]->penjualan_produk_id)}}
        </td>
        <td>
            {{$d->DetailPesananUnique()[0]->PenjualanProduk->harga}}
        </td>
        <td>
            {{$d->getOngkirPesananUnique($d->DetailPesananUnique()[0]->penjualan_produk_id)}}
        </td>
        <td>
            {{ ($d->getJumlahPesananUnique($d->DetailPesananUnique()[0]->penjualan_produk_id) * $d->DetailPesananUnique()[0]->PenjualanProduk->harga ) +   $d->getOngkirPesananUnique($d->DetailPesananUnique()[0]->penjualan_produk_id) }}
        </td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">
         {{$d->state->nama}}
        </td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">
            {{$d->Ekatalog->status}}
        </td>
        <td rowspan="{{$d->getJumlahPaketUnique()}}">
            @if($d->Ekatalog->ket != '')
            {{$d->Ekatalog->ket}}
            @else
            -
            @endif
        </td>
        </tr>
            {{-- @for ($i=1;$i<$d->getJumlahPaketUnique();$i++)
            <tr>
                <td>
                    @if ($d->getSuratJalanProduk($d->DetailPesananUnique()[$i]->penjualan_produk_id)->count() > 0)
                    @foreach($d->getSuratJalanProduk($d->DetailPesananUnique()[$i]->penjualan_produk_id) as $f)
                    {{ $f->Logistik->nosurat }}({{ date('d-m-Y', strtotime($f->Logistik->tgl_kirim)) }})
                    @if( !$loop->last)
                    ,
                    @endif
                    @endforeach
                @else
                -
                @endif
                </td>
                <td>
                    @if($d->DetailPesananUnique()[$i]->PenjualanProduk->nama_alias != '')
                         {{$d->DetailPesananUnique()[$i]->PenjualanProduk->nama_alias}}
                     @else
                          {{$d->DetailPesananUnique()[$i]->PenjualanProduk->nama}}
                     @endif
                </td>
                <td>
                    @if ($d->getNoseri($d->DetailPesananUnique()[$i]->penjualan_produk_id)->count() > 0)
                    @foreach($d->getNoseri($d->DetailPesananUnique()[$i]->penjualan_produk_id) as $g)
                    {{ $g->NoseriTGbj->seri->noseri}}
                    @if( !$loop->last)
                    ,
                    @endif
                    @endforeach
                    @else
                    -
                    @endif
                </td>
                <td>
                    {{$d->getJumlahPesananUnique($d->DetailPesananUnique()[$i]->penjualan_produk_id)}}
            </td>
            <td>
                {{$d->DetailPesananUnique()[$i]->PenjualanProduk->harga}}
            </td>
            <td>
                {{$d->getOngkirPesananUnique($d->DetailPesananUnique()[0]->penjualan_produk_id)}}
            </td>
            <td>
                {{ ($d->getJumlahPesananUnique($d->DetailPesananUnique()[$i]->penjualan_produk_id) * $d->DetailPesananUnique()[$i]->PenjualanProduk->harga ) +   $d->getOngkirPesananUnique($d->DetailPesananUnique()[$i]->penjualan_produk_id) }}
            </td>
            </tr>
            @endfor --}}
@endforeach
    </tbody>
</table>



