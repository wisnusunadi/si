<table>
    <thead>
        <tr>
            <th>No</th>
            <th style="width: 300px">Nama Produk</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $d)
            <tr>
                <td>{{ $no++ }}</td>
                @if (isset($d->nama))
                    <td>{{ $d->produk->nama }} {{ $d->nama }}</td>
                @else
                    <td>{{ $d->produk->nama }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
