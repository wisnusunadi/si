<table>
    <thead>
    <tr>
        <th colspan="2">Tanggal</th>
        <th rowspan="2">Dari/Ke</th>
        <th rowspan="2">Jenis</th>
        <th rowspan="2">Produk</th>
        <th rowspan="2">Jumlah</th>
        <th rowspan="2">Keterangan</th>
        <th rowspan="2">Noseri</th>
        <th rowspan="2">Catatan</th>
        <th rowspan="2">Tingkat Kerusakan</th>
        <th rowspan="2">Layout</th>
    </tr>
    <tr>
        <th>Masuk</th>
        <th>Keluar</th>
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
            <td>{{ $d['Noseri']['layout'] }}</td>
            <td>{{ $d['Noseri']['tingkat'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
