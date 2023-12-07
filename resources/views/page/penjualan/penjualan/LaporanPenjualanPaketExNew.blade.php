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
            <th>Produk</th>
            <th>Produk (E-purchasing)</th>
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
        @foreach ($data as $d)
            <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d['so']}}</td>
            <td>{{$d['po']}}</td>
            <td>{{$d['tgl_po']}}</td>
            <td>
                @if (count($d['nosurat']) > 0)
                @foreach ($d['nosurat'] as $f)
                {{ $f['nosurat']}}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                @if (!$loop->last),@endif
                @endforeach
                @endif
                @if (count($d['nosurat_part']) > 0)
                @foreach ($d['nosurat_part'] as $f)
                {{ $f['nosurat']}}({{ date('d-m-Y', strtotime($f['tgl_kirim'])) }})
                @if (!$loop->last),@endif
                @endforeach
                @endif
            </td>
            <td>{{$d['no_urut']}}</td>
            <td>{{$d['no_paket']}}</td>
            <td>{{$d['nama']}}</td>
            <td>{{$d['instansi']}}</td>
            <td>{{$d['alamat_instansi']}}</td>
            <td>{{$d['satuan']}}</td>
            <td>{{$d['satuan']}}</td>
            <td>
                @if ($d['tgl_buat'] != '-')
                {{ date('d-m-Y', strtotime($d['tgl_buat'])) }}
                @endif
            </td>
            <td>
                @if ($d['tgl_kontrak'] != '-')
                {{ date('d-m-Y', strtotime($d['tgl_kontrak'])) }}
                @endif
           </td>
            </tr>
        @endforeach

    </tbody>
</table>
