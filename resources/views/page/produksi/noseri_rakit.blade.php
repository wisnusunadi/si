{{-- <table>
    <thead>
        <tr>
            <th colspan="7">Hasil Perakitan Produksi</th>
        </tr>

        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nomor BPPB</th>
            <th rowspan="2">Produk</th>
            <th colspan="2">Tanggal</th>
            <th rowspan="2">Status</th>
            <th rowspan="2">Noseri</th>
        </tr>
        <tr>
            <th>Rakit</th>
            <th>Transfer</th>
        </tr>
    </thead>

    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $d)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $d->header->no_bppb }}</td>
                <td>{{ $d->header->produk->produk->nama }} {{ $d->header->produk->nama }}</td>
                <td>{{ $d->date_in }}</td>
                <td>{{ $d->waktu_tf }}</td>
                <td>{{ $d->status == 11 ? 'Rakit' : 'Transfer' }}</td>
                <td>{{ $d->noseri }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}

<table>
    <thead>
        <tr>
            <th> HASIL PERAKITAN PRODUKSI</th>
        </tr>
        <tr></tr>
        @foreach ($data as $d)
        @php
            $no = 0;
        @endphp
            <tr>
                <th colspan="2">Nomor BPPB</th>
                <td colspan="3">{{ $d->no_bppb == null ? '-' : $d->no_bppb }}</td>
            </tr>
            <tr>
                <th colspan="2">Produk</th>
                <td colspan="5">{{ $d->produk->produk->nama }} {{ $d->produk->nama }}</td>
            </tr>
            <tr>
                <th colspan="2">Jumlah</th>
                <td>{{ $d->jumlah }}</td>
            </tr>
            <tr>
            @foreach ($d->noseri as $dd)
            @php
                $no++;
            @endphp
                <td>{{ $no }}</td>
                @if (isset($dd->noseri))
                    <td>{{ $dd->noseri }}</td>
                @else
                    <td>-</td>
                @endif
                <td>{{ $dd->date_in }}</td>
                <td>{{ $dd->waktu_tf == null ? '-' : $dd->waktu_tf }}</td>
                @if ($no % 5 == 0)
                    </tr><tr>
                @endif
            @endforeach
            </tr>
            <tr></tr>

        @endforeach
    </thead>
</table>
