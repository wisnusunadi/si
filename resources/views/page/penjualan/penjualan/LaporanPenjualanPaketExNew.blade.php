<style>
    .va {
        vertical-align: bottom;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="22" style="text-align:center">
                Laporan Penjualan
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
            <th>Alamat Instansi</th>
            <th>Satuan</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Jenis</th>
            <th>Produk</th>
            <th>Produk (E-purchasing)</th>
            <th>No Seri</th>
            <th>Jumlah</th>
            @if ($divisi == 'Penjualan')
                <th>Harga</th>
                <th>Ongkir</th>
                <th>Subtotal</th>
            @endif
            <th>Status AKN</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($data as $key => $d)
            @foreach ($d['produk'] as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d['so'] }}</td>
                    <td>{{ $d['po'] }}</td>
                    <td>{{ $d['tgl_po'] }}</td>
                    <td>
                        @if (count($d['nosurat']) > 0)
                            @foreach ($d['nosurat'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                        @if (count($d['nosurat_part']) > 0)
                            @foreach ($d['nosurat_part'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $d['no_urut'] }}</td>
                    <td>{{ $d['no_paket'] }}</td>
                    <td>{{ $d['nama'] }}</td>
                    <td>{{ $d['instansi'] }}</td>
                    <td>{{ $d['alamat_instansi'] }}</td>
                    <td>{{ $d['satuan'] }}</td>
                    <td>
                        {{ $d['tgl_buat'] }}
                    </td>
                    <td>
                        {{ $d['tgl_kontrak'] }}
                    </td>
                    <td>nondsb</td>
                    <td>{{ $p['nama'] }}</td>
                    <td>{{ $p['nama_alias'] }}</td>
                    <td>
                        @if ($seri == 'seri')
                            @foreach ($p['seri'] as $s)
                                {{ $s['noseri'] }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @else
                            -
                        @endif

                    </td>
                    <td>{{ $p['jumlah'] }}</td>
                    @if ($divisi == 'Penjualan')
                        <td>{{ $p['harga'] }}</td>
                        <td>{{ $p['ongkir'] }}</td>
                        <td>{{ $p['jumlah'] * $p['harga'] + $p['ongkir'] }}</td>
                    @endif
                    <td>{{ $d['log_id'] == 20 ? 'batal' : $d['status'] }}</td>
                    <td>{{ $d['ket'] }}</td>
                </tr>
            @endforeach
            @foreach ($d['produk_dsb'] as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d['so'] }}</td>
                    <td>{{ $d['po'] }}</td>
                    <td>{{ $d['tgl_po'] }}</td>
                    <td>
                        @if (count($d['nosurat']) > 0)
                            @foreach ($d['nosurat'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                        @if (count($d['nosurat_part']) > 0)
                            @foreach ($d['nosurat_part'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $d['no_urut'] }}</td>
                    <td>{{ $d['no_paket'] }}</td>
                    <td>{{ $d['nama'] }}</td>
                    <td>{{ $d['instansi'] }}</td>
                    <td>{{ $d['alamat_instansi'] }}</td>
                    <td>{{ $d['satuan'] }}</td>
                    <td>
                        {{ $d['tgl_buat'] }}
                    </td>
                    <td>
                        {{ $d['tgl_kontrak'] }}
                    </td>
                    <td>dsb</td>
                    <td>{{ $p['nama'] }}</td>
                    <td>{{ $p['nama_alias'] }}</td>
                    <td>
                        @if ($seri == 'seri')
                            @foreach ($p['seri'] as $s)
                                {{ $s['noseri'] }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $p['jumlah'] }}</td>
                    @if ($divisi == 'Penjualan')
                        <td>{{ $p['harga'] }}</td>
                        <td>{{ $p['ongkir'] }}</td>
                        <td>{{ $p['jumlah'] * $p['harga'] + $p['ongkir'] }}</td>
                    @endif
                    <td>{{ $d['log_id'] == 20 ? 'batal' : $d['status'] }}</td>
                    <td>{{ $d['ket'] }}</td>
                </tr>
            @endforeach
            @foreach ($d['part'] as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d['so'] }}</td>
                    <td>{{ $d['po'] }}</td>
                    <td>{{ $d['tgl_po'] }}</td>
                    <td>
                        @if (count($d['nosurat']) > 0)
                            @foreach ($d['nosurat'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                        @if (count($d['nosurat_part']) > 0)
                            @foreach ($d['nosurat_part'] as $f)
                                {{ $f['nosurat'] }}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $d['no_urut'] }}</td>
                    <td>{{ $d['no_paket'] }}</td>
                    <td>{{ $d['nama'] }}</td>
                    <td>{{ $d['instansi'] }}</td>
                    <td>{{ $d['alamat_instansi'] }}</td>
                    <td>{{ $d['satuan'] }}</td>
                    <td>
                        {{ $d['tgl_buat'] }}
                    </td>
                    <td>
                        {{ $d['tgl_kontrak'] }}
                    </td>
                    <td>nondsb</td>
                    <td>{{ $p['nama'] }}</td>
                    <td>{{ $p['nama'] }}</td>
                    <td>-</td>
                    <td>{{ $p['jumlah'] }}</td>
                    @if ($divisi == 'Penjualan')
                        <td>{{ $p['harga'] }}</td>
                        <td>0</td>
                        <td>{{ $p['jumlah'] * $p['harga'] }}</td>
                    @endif
                    <td>{{ $d['log_id'] == 20 ? 'batal' : $d['status'] }}</td>
                    <td>{{ $d['ket'] }}</td>
                </tr>
            @endforeach
            @if (count($d['produk']) == 0 && count($d['part']) == 0 && count($d['produk_dsb']) == 0)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d['so'] }}</td>
                    <td>{{ $d['po'] }}</td>
                    <td>{{ $d['tgl_po'] }}</td>
                    <td></td>
                    <td>{{ $d['no_urut'] }}</td>
                    <td>{{ $d['no_paket'] }}</td>
                    <td>{{ $d['nama'] }}</td>
                    <td>{{ $d['instansi'] }}</td>
                    <td>{{ $d['alamat_instansi'] }}</td>
                    <td>{{ $d['satuan'] }}</td>
                    <td>
                        {{ $d['tgl_buat'] }}
                    </td>
                    <td>
                        {{ $d['tgl_kontrak'] }}
                    </td>
                    <td>nondsb</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if ($divisi == 'Penjualan')
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td>{{ $d['log_id'] == 20 ? 'batal' : $d['status'] }}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
