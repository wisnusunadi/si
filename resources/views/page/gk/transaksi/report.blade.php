<table>
    <thead>
    <tr>
        <th colspan="2"><b>Tanggal</b></th>
        <th rowspan="2"><b>Dari/Ke</b></th>
        <th rowspan="2"><b>Jenis</b></th>
        <th rowspan="2"><b>Produk</b></th>
        <th rowspan="2"><b>Jumlah</b></th>
        <th rowspan="2"><b>Keterangan</b></th>
        <th rowspan="2"><b>Noseri</b></th>
        <th rowspan="2"><b>Kerusakan</b></th>
        <th rowspan="2"><b>Perbaikan</b></th>
        <th rowspan="2"><b>Posisi Barang</b></th>
        <th rowspan="2"><b>Tingkat Kerusakan</b></th>
    </tr>
    <tr>
        <th><b>Masuk</b></th>
        <th><b>Keluar</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $d)
        <tr>
            <td>{{ $d['Masuk'] }}</td>
            <td>{{ $d['Keluar'] }}</td>
            <td>{{ $d['Dari/Ke'] }}</td>
            <td>{{ $d['Status'] }}</td>
            <td>{{ $d['Produk'] }}</td>
            <td>{{ $d['Jumlah'] }}</td>
            <td>{{ $d['Keterangan'] }}</td>
            <td>{{ $d['Noseri']['seri'] }}</td>
            <td>{{ $d['Noseri']['remark'] }}</td>
            <td>{{ $d['Noseri']['perbaikan'] }}</td>
            <td>{{ $d['Noseri']['layout'] }}</td>
            <td>{{ $d['Noseri']['tingkat'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
