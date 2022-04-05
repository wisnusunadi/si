<table style="border:1px solid #000">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
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
            <th>No Seri</th>
            <th>Hasil</th>
            @endif
            @if($jenis == "part")
            <th>Nama Part</th>
            <th>Jumlah OK</th>
            <th>Jumlah NOK</th>
            @endif
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i)
        <?php
        $count = 0;
        if($jenis == "produk"){
            $count = $i->getJumlahCekSeri();
        }
        else if($jenis == "part"){
            $count = count($i->DetailPesananPartNonJasa());
        }
        ?>
            <tr>
                <td>{{$loop->iteration}} {{$count}}</td>
                <td>{{$i->so}}</td>
                <td>
                    @if($i->Ekatalog)
                    {{$i->no_paket}}
                    @else
                    -
                    @endif
                </td>
                <td>{{$i->no_po}}</td>
                <td>{{$i->tgl_po}}</td>
                <td>@if($i->Ekatalog)
                    {{$i->Ekatalog->Customer->nama}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->nama}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->nama}}
                    @endif
                </td>
                <td>@if($i->Ekatalog)
                    {{$i->Ekatalog->instansi}}
                    @else
                    -
                    @endif
                </td>
                <td>@if($i->Ekatalog)
                    {{$i->Ekatalog->satuan}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($i->Ekatalog)
                    {{$i->Ekatalog->alamat}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->alamat}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->alamat}}
                    @endif
                </td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
