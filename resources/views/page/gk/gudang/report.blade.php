<table>
    <thead>
    <tr>
        <th><b>Kode</b></th>
        <th><b>Nama</b></th>
        <th><b>Jumlah</b></th>
        <th><b>Jenis</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $d)
        <tr>
            <td>{{ $d['kode'] }}</td>
            <td>{{ $d['nama'] }}</td>
            <td>{{ $d['jml'] }}</td>
            <td>{{ $d['jenis'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
