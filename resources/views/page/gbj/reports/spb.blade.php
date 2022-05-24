    <table>
        @foreach ($data as $d)
        @php
            $i = 0;
        @endphp
            <tr align="center">
                <td colspan=4 align="center">{{ $d->produk->produk->nama.' '.$d->produk->nama }}</td>
                <th rowspan=2 align="center">{{ count($d->seri) }}</th>
            </tr>
            <tr align="center">
                <th colspan=4 align="center"><b>({{ $d->paket->detailpesanan->penjualanproduk->nama }})</b></th>
            </tr>
            <tr>
        @foreach($d->seri as $r)
            @php
                $i++;
            @endphp
            <td>({{ $i }}) {{ $r->seri->noseri }}</td>
            @if ($i % 5 == 0)
                </tr><tr>
            @endif
        @endforeach
            </tr>
        @endforeach

    </table>

