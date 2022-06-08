
<table>
    <thead>
        <tr>
            <td></td>
            <td>Data Transfer Gudang Barang Jadi ke QC </td>
        </tr>
        <tr>
            <td></td>
            <td>PO  </td>
            <td><b>{{$header->no_po}}</b></td>
        </tr>
        <tr>
            <td></td>
            <td>
                Distributor \ Customer
            </td>
            <td>
                <b>
                @if($header->Ekatalog)
                {{$header->Ekatalog->Customer->nama}}
                 @elseif($header->Spa)
                  {{$header->Spa->Customer->nama}}
                 @elseif($header->Spb)
                 {{$header->Spb->Customer->nama}}
                 @endif
                </b>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td>No</td>
            <td>Nama</td>
            <td>Seri</td>
            <td>Status Cek QC</td>
        </tr>
    </thead>
    <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $d )

                @foreach ($d->TFProduksiDetail as $index => $e)
                <tr>
                    <td rowspan="{{$e->noseri->count()}}">{{$no++}}</td>
                    <td rowspan="{{$e->noseri->count()}}">
                        @if($e->paket->Gudangbarangjadi->nama != '')
                        {{$e->paket->Gudangbarangjadi->produk->nama}} - {{$e->paket->Gudangbarangjadi->nama}}
                        @else
                        {{$e->paket->GudangBarangJadi->produk->nama}}
                        @endif
                    </td>
                    <td>{{$e->noseri[0]->seri->noseri}}</td>
                    <td>
                        @if($e->noseri[0]->NoseriDetailPesanan)
                                @if ($e->noseri[0]->NoseriDetailPesanan->status == 'ok')
                                OK
                                @else
                                NOK
                                @endif
                        @else
                        Belum di check
                        @endif
                    </td>
                </tr>
                @for ($i=1;$i<$e->noseri->count();$i++)
                <tr>
                    <td>{{$e->noseri[$i]->seri->noseri}}</td>
                    <td>
                        @if($e->noseri[$i]->NoseriDetailPesanan)
                                @if ($e->noseri[$i]->NoseriDetailPesanan->status == 'ok')
                                OK
                                @else
                                NOK
                                @endif
                        @else
                        Belum di check
                        @endif
                    </td>
                </tr>
                @endfor
                @endforeach
            @endforeach
    </tbody>
</table>
