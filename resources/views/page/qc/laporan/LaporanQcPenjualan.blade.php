<table style="border:1px solid #000">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                Laporan Pengiriman {{ucfirst($jenis)}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No AKN</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Customer</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Alamat</th>
            @if($jenis == "produk")
            <th>Nama Produk</th>
            <th>Produk</th>
            <th>No Seri</th>
            <th>Tanggal Uji</th>
            <th>Hasil</th>
            @elseif($jenis == "part")
            <th>Nama Part</th>
            <th>Jumlah OK</th>
            <th>Jumlah NOK</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i)
        <?php
        $count = 0;
        if($jenis == "produk"){
            $count = $i->countLaporanQcProduk($produk, $hasil, $tgl_awal, $tgl_akhir);
        }
        else if($jenis == "part"){
            $count = count($i->DetailPesananPartNonJasa());
        }
        ?>
            <tr>
                <td rowspan="{{$count}}">{{$loop->iteration}}</td>
                <td rowspan="{{$count}}">{{$i->so}}</td>
                <td rowspan="{{$count}}">
                    @if($i->Ekatalog)
                    {{$i->no_paket}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$count}}">{{$i->no_po}}</td>
                <td rowspan="{{$count}}">{{$i->tgl_po}}</td>
                <td rowspan="{{$count}}">@if($i->Ekatalog)
                    {{$i->Ekatalog->Customer->nama}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->nama}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->nama}}
                    @endif
                </td>
                <td rowspan="{{$count}}">@if($i->Ekatalog)
                    {{$i->Ekatalog->instansi}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$count}}">@if($i->Ekatalog)
                    {{$i->Ekatalog->satuan}}
                    @else
                    -
                    @endif
                </td>
                <td rowspan="{{$count}}">
                    @if($i->Ekatalog)
                    {{$i->Ekatalog->alamat}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->alamat}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->alamat}}
                    @endif
                </td>
                @if($jenis == "part")
                @foreach ($i->LaporanQcProduk($produk, $hasil, $tgl_awal, $tgl_akhir) as $j)
                    <?php $cdp = 0; $rowdp = $j->countLaporanQcProduk($hasil, $tgl_awal, $tgl_akhir);?>
                    @if($cdp <= 0)
                        <td rowspan="{{$rowdp}}"> @if($j->PenjualanProduk->nama_alias != '') {{$j->PenjualanProduk->nama_alias}} @else {{$j->PenjualanProduk->nama}} @endif</td>
                        @foreach ($j->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $k)
                            <?php $cdpp = 0;  $rowddp = $k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count(); ?>
                            @if($cdpp <= 0)
                            <td rowspan="{{$rowddp}}">{{$k->GudangBarangJadi->Produk->nama}} {{$k->GudangBarangJadi->nama}}</td>
                                @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l)
                                    <?php $cndp = 0; ?>
                                    @if($cndp <= 0)
                                    <td>
                                        {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                    </td>
                                    <td>{{$l->tgl_uji}}</td>
                                    <td>{{$l->status}}</td>
                                    @else
                                    <tr>
                                    <td>
                                        {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                    </td>
                                    <td>{{$l->tgl_uji}}</td>
                                    <td>{{$l->status}}</td>
                                    @endif
                                    <?php $cndp++; ?>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                            <td rowspan="{{$rowddp}}">{{$k->GudangBarangJadi->Produk->nama}} {{$k->GudangBarangJadi->nama}}</td>
                                @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l)
                                    <?php $cndp = 0; ?>
                                    @if($cndp <= 0)
                                    <td>
                                        {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                    </td>
                                    <td>{{$l->tgl_uji}}</td>
                                    <td>{{$l->status}}</td>
                                    @else
                                    <tr>
                                    <td>
                                        {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                    </td>
                                    <td>{{$l->tgl_uji}}</td>
                                    <td>{{$l->status}}</td>
                                    @endif
                                    <?php $cndp++; ?>
                                </tr>
                                @endforeach
                            @endif
                            <?php $cdpp++; ?>
                        @endforeach
                    @else
                        <tr>
                        <td rowspan="{{$rowdp}}"> @if($j->PenjualanProduk->nama_alias != '') {{$j->PenjualanProduk->nama_alias}} @else {{$j->PenjualanProduk->nama}} @endif </td>
                        @foreach ($j->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $k)
                            <?php $cdpp = 0; $rowddp = $k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count(); ?>
                            @if($cdpp <= 0)
                                <td rowspan="{{$rowddp}}">{{$k->GudangBarangJadi->Produk->nama}} {{$k->GudangBarangJadi->nama}}</td>
                                @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l)
                                    <?php $cndp = 0; ?>
                                    @if($cndp <= 0)
                                        <td>
                                            {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                        </td>
                                        <td>{{$l->tgl_uji}}</td>
                                        <td>{{$l->status}}</td>
                                    @else
                                        <tr>
                                        <td>
                                            {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                        </td>
                                        <td>{{$l->tgl_uji}}</td>
                                        <td>{{$l->status}}</td>
                                    @endif
                                    <?php $cndp++; ?>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                <td rowspan="{{$rowddp}}">{{$k->GudangBarangJadi->Produk->nama}} {{$k->GudangBarangJadi->nama}}</td>
                                @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l)
                                    <?php $cndp = 0; ?>
                                    @if($cndp <= 0)
                                        <td>
                                            {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                        </td>
                                        <td>{{$l->tgl_uji}}</td>
                                        <td>{{$l->status}}</td>
                                    @else
                                        <tr>
                                        <td>
                                            {{-- {{$l->NoseriTGbj->NoseriBarangJadi->noseri}} --}}
                                        </td>
                                        <td>{{$l->tgl_uji}}</td>
                                        <td>{{$l->status}}</td>
                                    @endif
                                    <?php $cndp++; ?>
                                    </tr>
                                @endforeach
                            @endif
                            <?php $cdpp++; ?>
                        @endforeach
                    @endif
                <?php $cdp++; ?>
                @endforeach
                @elseif($jenis == "produk")
                @foreach ($i->LaporanQcProduk($produk, $hasil, $tgl_awal, $tgl_akhir) as $j)
                <?php $count_detail_pesanan = 0; ?>
                @if($count_detail_pesanan <= 0)
                    <td rowspan="{{$j->countLaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)}}">{{$j->PenjualanProduk->nama}}  </td>
                    @foreach ($j->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $k)
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}">{{$k->GudangBarangJadi->Produk->nama}}</td>
                    {{-- @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l) --}}
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    </tr>
                    {{-- @endforeach --}}
                    @endforeach
                @else
                    <tr>
                    <td rowspan="{{$j->countLaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)}}">{{$j->PenjualanProduk->nama}}  </td>
                    @foreach ($j->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $k)
                    <?php $count_detail_pesanan_produk = 0; ?>
                    @if($count_detail_pesanan_produk <= 0)
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}">{{$k->GudangBarangJadi->Produk->nama}}</td>
                    {{-- @foreach($k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir) as $l) --}}
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    <td rowspan="{{$k->LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)->count()}}"></td>
                    {{-- @endforeach --}}
                    @else

                    @endif
                    </tr>
                    @endforeach
                @endif
                <?php $count_detail_pesanan++; ?>
                @endforeach
                @endif
            {{-- </tr> --}}
        @endforeach
    </tbody>
</table>
