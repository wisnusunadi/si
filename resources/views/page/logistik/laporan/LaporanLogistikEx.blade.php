<table style="border:1px solid #000">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                @if($header == 'ekspedisi')
                Laporan Pengiriman Ekspedisi
                @foreach ($data as $d)
                @endforeach
                @elseif ($header == 'nonekspedisi')
                Laporan Pengiriman Non Ekspedisi
                @else
                Laporan Pengiriman Ekspedisi dan Non Ekspedisi
                @endif
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Customer</th>
            <th>Alamat</th>
            <th>Provinsi</th>
            <th>No SJ</th>
            <th>Tanggal Kirim</th>
            <th>No Resi</th>
            <th>Jasa Ekspedisi</th>
            <th>Nama Produk</th>
            <th>Variasi</th>
            <th>Jumlah</th>
            <th>No Seri</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $i)
            <?php
                $clog = 0;
                $cso = $i->AllProdukKirim($header, $eks, $awal, $akhir);
            ?>
            <tr>
                <td rowspan="{{$cso}}">{{$loop->iteration}}</td>
                <td rowspan="{{$cso}}">{{$i->so}}</td>
                <td rowspan="{{$cso}}">{{$i->no_po}}</td>
                <td rowspan="{{$cso}}">
                    @if($i->Ekatalog)
                    {{$i->Ekatalog->instansi}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->nama}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->nama}}
                    @endif
                </td>
                <td rowspan="{{$cso}}">
                    @if($i->Ekatalog)
                    {{$i->Ekatalog->alamat}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->alamat}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->alamat}}
                    @endif
                </td>
                <td rowspan="{{$cso}}">
                    @if($i->Ekatalog)
                    {{$i->Ekatalog->Provinsi->nama}}
                    @elseif($i->Spa)
                    {{$i->Spa->Customer->Provinsi->nama}}
                    @elseif($i->Spb)
                    {{$i->Spb->Customer->Provinsi->nama}}
                    @endif
                </td>
                @foreach ($i->LogistikLaporan($header, $eks, $awal, $akhir) as $j)
                    <?php
                    $cdlog = 0; $rowspan = 0; $prd = 0; $part = 0;
                    if($j->DetailLogistikPart){
                        $part = $j->DetailLogistikPart->count();
                    }
                    if($j->DetailLogistik){
                        $prd = $j->DetailLogistik->count();
                    }
                    $rowspan = $prd + $part;
                    ?>
                    @if($clog <= 0)
                        <td rowspan="{{$rowspan}}">{{$j->nosurat}}</td>
                        <td rowspan="{{$rowspan}}">{{date('d-m-Y', strtotime($j->tgl_kirim))}}</td>
                        <td rowspan="{{$rowspan}}">{{$j->noresi}}</td>
                        <td rowspan="{{$rowspan}}">
                            @if($j->ekspedisi_id)
                            {{$j->Ekspedisi->nama}}
                            @else
                            {{$j->nama_pengirim}}
                            @endif
                        </td>
                        @if($j->DetailLogistikPart)
                            @foreach ($j->DetailLogistikPart as $k)
                                @if($cdlog <= 0)
                                    <td>{{$k->DetailPesananPart->Sparepart->nama}}</td>
                                    <td>-</td>
                                    <td>{{$k->DetailPesananPart->jumlah}}</td>
                                    <td>-</td>
                                    @if($clog <= 0)
                                    <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                                    @endif
                                @else
                                <tr>
                                    <td>{{$k->DetailPesananPart->Sparepart->nama}}</td>
                                    <td>-</td>
                                    <td>{{$k->DetailPesananPart->jumlah}}</td>
                                    <td>-</td>
                                @endif
                                </tr>
                                <?php
                                $cdlog++;
                                ?>
                            @endforeach
                        @endif
                        @if($j->DetailLogistik)
                            @foreach ($j->DetailLogistik as $k)
                                @if($cdlog <= 0)
                                    <td>
                                        @if($k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                                        @else
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$k->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$k->DetailPesananProduk->GudangBarangJadi->nama}}
                                    </td>
                                    <td>
                                        {{$k->NoseriDetailLogistik->count()}}
                                    </td>
                                    <td>
                                        @if($k->NoseriDetailLogistik)
                                            @foreach ($k->NoseriDetailLogistik as $k)
                                                {{ $loop->first ? '' : ', ' }}
                                                {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                            @endforeach
                                        @endif
                                    </td>
                                    @if($clog <= 0)
                                    <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                                    @endif
                                @else
                                <tr>
                                    <td>
                                        @if($k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                                        @else
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$k->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$k->DetailPesananProduk->GudangBarangJadi->nama}}
                                    </td>
                                    <td>
                                        {{$k->NoseriDetailLogistik->count()}}
                                    </td>
                                    <td>
                                        @if($k->NoseriDetailLogistik)
                                            @foreach ($k->NoseriDetailLogistik as $k)
                                                {{ $loop->first ? '' : ', ' }}
                                                {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                            @endforeach
                                        @endif
                                    </td>
                                @endif
                                </tr>
                            <?php
                            $cdlog++;
                            ?>
                            @endforeach
                        @endif
                    @else
                    <tr>
                        <td rowspan="{{$rowspan}}">{{$j->nosurat}}</td>
                        <td rowspan="{{$rowspan}}">{{date('d-m-Y', strtotime($j->tgl_kirim))}}</td>
                        <td rowspan="{{$rowspan}}">{{$j->noresi}}</td>
                        <td rowspan="{{$rowspan}}">
                            @if($j->ekspedisi_id)
                            {{$j->Ekspedisi->nama}}
                            @else
                            {{$j->nama_pengirim}}
                            @endif
                        </td>
                        @if($j->DetailLogistikPart)
                            @foreach ($j->DetailLogistikPart as $k)
                                @if($cdlog <= 0)
                                    <td>{{$k->DetailPesananPart->Sparepart->nama}}</td>
                                    <td>-</td>
                                    <td>{{$k->DetailPesananPart->jumlah}}</td>
                                    <td>-</td>
                                    @if($clog <= 0)
                                    <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                                    @endif
                                @else
                                <tr>
                                    <td>{{$k->DetailPesananPart->Sparepart->nama}}</td>
                                    <td>-</td>
                                    <td>{{$k->DetailPesananPart->jumlah}}</td>
                                    <td>-</td>
                                @endif
                                </tr>
                                <?php
                                $cdlog++;
                                ?>
                            @endforeach
                        @endif
                        @if($j->DetailLogistik)
                            @foreach ($j->DetailLogistik as $k)
                                @if($cdlog <= 0)
                                    <td>
                                        @if($k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                                        @else
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$k->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$k->DetailPesananProduk->GudangBarangJadi->nama}}
                                    </td>
                                    <td>
                                        {{$k->NoseriDetailLogistik->count()}}
                                    </td>
                                    <td>
                                        @if($k->NoseriDetailLogistik)
                                            @foreach ($k->NoseriDetailLogistik as $k)
                                                {{ $loop->first ? '' : ', ' }}
                                                {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                            @endforeach
                                        @endif
                                    </td>
                                    @if($clog <= 0)
                                    <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                                    @endif
                                @else
                                <tr>
                                    <td>
                                        @if($k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                                        @else
                                        {{$k->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$k->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$k->DetailPesananProduk->GudangBarangJadi->nama}}
                                    </td>
                                    <td>
                                        {{$k->NoseriDetailLogistik->count()}}
                                    </td>
                                    <td>
                                        @if($k->NoseriDetailLogistik)
                                            @foreach ($k->NoseriDetailLogistik as $k)
                                                {{ $loop->first ? '' : ', ' }}
                                                {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                            @endforeach
                                        @endif
                                    </td>
                                @endif
                                </tr>
                                <?php
                                $cdlog++;
                                ?>
                            @endforeach
                        @endif

                    @endif
                    <?php
                    $clog++;
                    ?>
                @endforeach
        @endforeach
    </tbody>

    {{-- <tbody>
        <?php
            // $so = ""; $po = ""; $cust = ""; $add = ""; $prov = ""; $cso = 0; $csolog = 0; $index = 0;
        ?>
        {{-- @foreach ($data as $i) --}}
        <?php
        // $clog = 0;
        // $cdlog = 0;
        // $rowspan = 0;
        // $prd = 0;
        // $part = 0;
        // if($i->DetailLogistikPart){
        //     $part = $i->DetailLogistikPart->count();
        // }
        // if($i->DetailLogistik){
        //     $prd = $i->DetailLogistik->count();
        // }

        // $rowspan = $prd + $part;
        ?>
        {{-- @if($i->DetailLogistik)
            @if(isset($i->DetailLogistik[0]))
                @if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so != "")
                    @if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so !== $so) --}}
                        <?php
                        // $so = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so;
                        // $po = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po;
                        // if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog){
                        //     $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                        //     $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat;
                        //     $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                        // }else if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa){
                        //     $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                        //     $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                        //     $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                        // }else if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb){
                        //     $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                        //     $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                        //     $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                        // }

                        // if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->LogistikLaporan() > 0){
                        //     $csolog = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->LogistikLaporan();
                        // }
                        // $cso = 0;
                        // $index++;
                        ?>
                    {{-- @endif
                @else --}}
                <?php
                // $so = "";
                // $cso = 0;
                // $index++;
                ?>
                {{-- @endif
            @endif
        @endif
        @if($i->DetailLogistikPart)
            @if(isset($i->DetailLogistikPart[0]))
                @if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so != "")
                @if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so !== $so) --}}
                    <?php
                    // $so = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so;
                    // $po = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->no_po;
                    // if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog){
                    //     $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->instansi;
                    //     $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->alamat;
                    //     $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->Provinsi->nama;
                    // }else if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa){
                    //     $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->nama;
                    //     $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                    //     $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                    // }else if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb){
                    //     $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->nama;
                    //     $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                    //     $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                    // }
                    // if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->LogistikLaporan() > 0){
                    //     $csolog = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->LogistikLaporan();
                    // }
                    // $cso = 0;
                    // $index++;
                    ?>
                {{-- @endif
                @else --}}
                <?php
                // $so = "";
                // $cso = 0;
                // $index++;
                ?>
                {{-- @endif
            @endif
        @endif
        @if($cso <= 0)
            <tr>
                <td>{{$index}}</td>
                <td rowspan="{{$csolog}}">{{$so}}</td>
                <td rowspan="{{$csolog}}">{{$po}}</td>
                <td rowspan="{{$csolog}}">{{$cust}}</td>
                <td rowspan="{{$csolog}}">{{$add}}</td>
                <td rowspan="{{$csolog}}">{{$prov}}</td>
                <td rowspan="{{$rowspan}}">{{$i->nosurat}}</td>
                <td rowspan="{{$rowspan}}">{{date('d-m-Y', strtotime($i->tgl_kirim))}}</td>
                <td rowspan="{{$rowspan}}">{{$i->noresi}}</td>
                <td rowspan="{{$rowspan}}">
                @if($i->ekspedisi_id)
                {{$i->Ekspedisi->nama}}
                @else
                {{$i->nama_pengirim}}
                @endif
                </td>
                @if($i->DetailLogistikPart)
                    @foreach ($i->DetailLogistikPart as $j)
                        @if($cdlog <= 0)
                            <td>{{$j->DetailPesananPart->Sparepart->nama}}</td>
                            <td>-</td>
                            <td>{{$j->DetailPesananPart->jumlah}}</td>
                            <td>-</td>
                            <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                        @else
                        <tr>
                            <td>{{$j->DetailPesananPart->Sparepart->nama}}</td>
                            <td>-</td>
                            <td>{{$j->DetailPesananPart->jumlah}}</td>
                            <td>-</td>
                        @endif
                        </tr>
                        <?php
                        // $cdlog++;
                        ?>
                    @endforeach
                @endif
                @if($i->DetailLogistik)
                    @foreach ($i->DetailLogistik as $j)
                        @if($cdlog <= 0)
                        <td>
                            @if($j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                            @else
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                            @endif
                        </td>
                        <td>
                            {{$j->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$j->DetailPesananProduk->GudangBarangJadi->nama}}
                        </td>
                        <td>
                            {{$j->NoseriDetailLogistik->count()}}
                        </td>
                        <td>
                            @if($j->NoseriDetailLogistik)
                                @foreach ($j->NoseriDetailLogistik as $k)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                @endforeach
                            @endif
                        </td>
                        <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                    @else
                    <tr>
                        <td>
                            @if($j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                            @else
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                            @endif
                        </td>
                        <td>
                            {{$j->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$j->DetailPesananProduk->GudangBarangJadi->nama}}
                        </td>
                        <td>
                            {{$j->NoseriDetailLogistik->count()}}
                        </td>
                        <td>
                            @if($j->NoseriDetailLogistik)
                                @foreach ($j->NoseriDetailLogistik as $k)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                @endforeach
                            @endif
                        </td>
                    @endif
                    </tr>
                    <?php
                    // $cdlog++;
                    ?>
                    @endforeach
                @endif
            <?php
            // $cso++;
            ?>
        @else
            <tr>
            <td rowspan="{{$rowspan}}">{{$i->nosurat}}</td>
            <td rowspan="{{$rowspan}}">{{date('d-m-Y', strtotime($i->tgl_kirim))}}</td>
            <td rowspan="{{$rowspan}}">{{$i->noresi}}</td>
            <td rowspan="{{$rowspan}}">
                @if($i->ekspedisi_id)
                {{$i->Ekspedisi->nama}}
                @else
                {{$i->nama_pengirim}}
                @endif
            </td>
            @if($i->DetailLogistikPart)
                @foreach ($i->DetailLogistikPart as $j)
                    @if($cdlog <= 0)
                        <td>{{$j->DetailPesananPart->Sparepart->nama}}</td>
                        <td>-</td>
                        <td>{{$j->DetailPesananPart->jumlah}}</td>
                        <td>-</td>
                        <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                    @else
                    <tr>
                        <td>{{$j->DetailPesananPart->Sparepart->nama}}</td>
                        <td>-</td>
                        <td>{{$j->DetailPesananPart->jumlah}}</td>
                        <td>-</td>
                    @endif
                    </tr>
                    <?php
                    // $cdlog++;
                    ?>
                @endforeach
            @endif
            @if($i->DetailLogistik)
                @foreach ($i->DetailLogistik as $j)
                    @if($cdlog <= 0)
                        <td>
                            @if($j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias != '')
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                            @else
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama}}
                            @endif
                        </td>
                        <td>
                            {{$j->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$j->DetailPesananProduk->GudangBarangJadi->nama}}
                        </td>
                        <td>
                            {{$j->NoseriDetailLogistik->count()}}
                        </td>
                        <td>
                            @if($j->NoseriDetailLogistik)
                                @foreach ($j->NoseriDetailLogistik as $k)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                @endforeach
                            @endif
                        </td>
                        <td rowspan="{{$rowspan}}">{{$i->State->nama}}</td>
                    @else
                    <tr>
                        <td>
                            {{$j->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias}}
                        </td>
                        <td>
                            {{$j->DetailPesananProduk->GudangBarangJadi->Produk->nama}} {{$j->DetailPesananProduk->GudangBarangJadi->nama}}
                        </td>
                        <td>
                            {{$j->NoseriDetailLogistik->count()}}
                        </td>
                        <td>
                            @if($j->NoseriDetailLogistik)
                                @foreach ($j->NoseriDetailLogistik as $k)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $k->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                                @endforeach
                            @endif
                        </td>
                    @endif
                    </tr>
                <?php
                // $cdlog++;
                ?>
                @endforeach
            @endif
        @endif
        @endforeach
    </tbody> --}}
    {{-- <tbody>
        @foreach ($data as $d)
        <tr>
            <td style="text-align:left">{{$loop->iteration}}</td>
            <td style="text-align:left">
                @if(isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                @else
                {{$d->DetailPesananPart->Pesanan->so}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                @else
                {{$d->DetailPesananPart->Pesanan->no_po}}
                @endif
            </td>
            <td style="text-align:left">{{ $d->Logistik->nosurat}}</td>
            <td style="text-align:left">{{ $d->Logistik->tgl_kirim}}</td>
            <td style="text-align:left">
                @if($d->Logistik->noresi == "")
                -
                @else
                {{$d->Logistik->noresi}}
                @endif
            </td>
            <td style="text-align:left">
                <?php
                // if (isset($d->DetailPesananProduk)) {
                //     $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                //     if ($name[1] == 'EKAT') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                //     } else if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                //     }
                // } else {
                //     $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                //     if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananPart->Pesanan->Spa->Customer->nama;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananPart->Pesanan->Spb->Customer->nama;
                //     }
                // }
                ?>
            </td>
            <td style="text-align:left">
                <?php
                // if (isset($d->DetailPesananProduk)) {
                //     $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                //     if ($name[1] == 'EKAT') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                //     } else if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                //     }
                // } else {
                //     $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                //     if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                //     }
                // }
                ?>
            </td>
            <td style="text-align:left">
                <?php
                // if (isset($d->DetailPesananProduk)) {
                //     $name = explode('/', $d->DetailPesananProduk->DetailPesanan->pesanan->so);
                //     if ($name[1] == 'EKAT') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                //     } else if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                //     }
                // } else {
                //     $name = explode('/', $d->DetailPesananPart->Pesanan->so);
                //     if ($name[1] == 'SPA') {
                //         echo $d->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                //     } else if ($name[1] == 'SPB') {
                //         echo $d->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                //     }
                // }
                ?>
            </td>
            <td style="text-align:left">
                @if (!empty($d->Logistik->ekspedisi_id))
                {{ $d->Logistik->Ekspedisi->nama}}
                @else
                {{ $d->Logistik->nama_pengirim}}
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->DetailPesananProduk))
                {{$d->DetailPesananProduk->GudangBarangJadi->Produk->nama}}
                @else
                {{ $d->DetailPesananPart->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->DetailPesananProduk))
                @if ($d->DetailPesananProduk->GudangBarangJadi->nama != '')
                {{$d->DetailPesananProduk->GudangBarangJadi->nama}}
                @endif
                @endif
            </td>
            <td style="text-align:left">
                @if (isset($d->NoseriDetailLogistik))
                {{ $d->NoseriDetailLogistik->count()}}
                @else
                {{ $d->DetailPesananPart->jumlah;}}
                @endif
            </td>
            <td style="text-align:left">
                {{ $d->Logistik->State->nama}}
            </td>
        </tr>
        @endforeach
    </tbody> --}}
</table>
