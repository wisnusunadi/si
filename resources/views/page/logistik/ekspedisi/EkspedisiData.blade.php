<table border="1">
    <thead>
        <tr>
            <th ></th>
            <th style="text-align:center">
            Data Ekspedisi
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama </th>
            <th>Alamat</th>
            <th>Email</th>
            <th>Telp</th>
            <th>Jalur</th>
            <th>Jurusan</th>
            <th>Keterangan</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->alamat}}</td>
            <td>
                @if ($d->email != '')
                {{$d->email}}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($d->telp != '')
                {{$d->telp}}
                @else
                    -
                @endif
            </td>
            <td>
                @foreach ($d->JalurEkspedisi as $j)
                {{ $loop->first ? '' : ', ' }}
                {{ $j->nama }}
                @endforeach
            </td>
            <td>
                @if ($d->provinsi != '')

                @foreach ($d->Provinsi as $p)
                {{ $loop->first ? '' : ', ' }}
                {{ $p->nama }}
                @endforeach

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
        </tr>
        @endforeach
    </tbody>
</table>
