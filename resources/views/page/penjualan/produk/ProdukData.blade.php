<table border="1">
    <thead>
        <tr>
            <th ></th>
            <th style="text-align:center">
            Data Produk
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No AKD </th>
            <th>Merk</th>
            <th>Nama Alias</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>
            <?php $p_id = array();
             foreach ($d->produk as $p ){
                if($p->coo == 1){
               $p_id[] =  $p->no_akd;
                }
             }
             $x =  array_unique($p_id);

                foreach ($x as $y) {
                echo $y;
                }


             ?>

            </td>
            <td>
            <?php $p_id = array();
             foreach ($d->produk as $p ){
                if($p->coo == 1){
               $p_id[] =  $p->merk;
                }
             }
             $x =  array_unique($p_id);
             foreach ($x as $y) {
                echo $y;
                }
             ?>

            </td>

            <td>
                @if ($d->nama_alias != '')
                {{$d->nama_alias}}
                @else
                    -
                @endif
            </td>
            <td>{{$d->nama}}</td>
            <td>{{$d->harga}}</td>
            <td>
                @foreach ($d->produk as $j)
                {{ $loop->first ? '' : ', ' }}
                {{ $j->nama }}
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
