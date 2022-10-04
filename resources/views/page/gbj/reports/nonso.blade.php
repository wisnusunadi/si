<table>
    <tr>
        <td colspan=10 align="center"><b>Laporan Tanpa Sales Order</b></td>
    </tr>
    @foreach ($data as $d)
    @php
        $i = 0;
    @endphp
    <tr></tr>
    <tr>
        <td colspan="2">Divisi</td>
        <td colspan="2">{{ $d->nm_divisi }}</td>
    </tr>
    <tr>
        <td colspan="2">Tanggal Keluar</td>
        <td colspan="2">{{ $d->tgl_keluar }}</td>
    </tr>
    <tr>
        <td colspan="2">Keterangan</td>
        <td colspan="2">{{ $d->deskripsi }}</td>
    </tr>
    <tr></tr>
    <tr align="center">
        <td colspan="4" align="center">{{ $d->produkk }}</td>
        <td style="vertical-align:middle; text-align:center;">{{ $d->qty }}</td>
    </tr>
    <tr>
    @foreach ($d->noseri as $r)
        @php
            $i++;
        @endphp
        <td align="left">{{ $i }}.</td>
        <td>{{ $r->seri->noseri }}</td>
        @if ($i % 5 == 0)
            </tr><tr>
        @endif

    @endforeach
    </tr>
    @endforeach
</table>
