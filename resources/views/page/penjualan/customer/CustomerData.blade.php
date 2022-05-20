<table border="1">
    <thead>
        <tr>
            <th ></th>
            <th style="text-align:center">
            Data Customer/ Distributor
            </th>
        </tr>
        <tr>
            <th ></th>
            <th colspan="8" ></th>
            <th colspan="4" style="text-align:center">
            Informasi Tambahan
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama </th>
            <th>Alamat</th>
            <th>Provinsi</th>
            <th>Email</th>
            <th>Telp</th>
            <th>NPWP</th>
            <th>KTP</th>
            <th>Keterangan</th>
            <th>Nama Pemilik</th>
            <th>Izin Usaha</th>
            <th>Modal Usaha</th>
            <th>Hasil Usaha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->alamat}}</td>
            <td>{{$d->provinsi->nama}}</td>
            <td>
            @if ($d->email != '')
            {{$d->email}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->telp != '' && $d->telp != 0)
            {{$d->telp}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->npwp != '')
            {{$d->npwp}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->ktp != '')
            {{$d->ktp}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->ket != '')
            {{$d->ket}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->nama_pemilik != '')
            {{$d->nama_pemilik}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->izin_usaha != '')
            {{$d->izin_usaha}}
            @else
                -
            @endif
            </td>
            <td>
            @if ($d->modal_usaha != '')

                @if ($d->modal_usaha == 1)
                kurang dari 1 M
                @elseif ($d->modal_usaha == 2)
                lebih dari 1 M dan kurang dari 5 M
                @elseif ($d->modal_usaha == 3)
                lebih dari 5 M dan kurang dari 10 M
                @endif

            @else
                -
            @endif

            </td>
            <td>

            @if ($d->hasil_penjualan != '')

                @if ($d->hasil_penjualan == 1)
                kurang dari 2 M
                @elseif ($d->hasil_penjualan == 2)
                lebih dari 2 M dan kurang dari 15 M
                @elseif ($d->hasil_penjualan == 3)
                lebih dari 15 M dan kurang dari 50 M
                @endif

            @else
                -
            @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
