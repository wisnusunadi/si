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
        <?php $so = ""; $po = ""; $cust = ""; $add = ""; $prov = ""; $cso = 0; $csolog = 1; $index = 0;?>
        @foreach ($data as $i)
        <?php $clog = 0;
        $cdlog = 0;
        $rowspan = 0;
        $prd = 0;
        $part = 0;
        if($i->DetailLogistik){
            $prd = $i->DetailLogistik->count();
        }
        if($i->DetailLogistikPart){
            $part = $i->DetailLogistikPart->count();
        }
        $rowspan = $prd + $part;
        ?>
        @if($i->DetailLogistik)
            @if(isset($i->DetailLogistik[0]))
                @if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so != "")
                    @if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so !== $so)
                        <?php
                        $so = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so;
                        $po = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po;
                        if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog){
                            $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                            $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat;
                            $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                        }else if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa){
                            $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                            $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                            $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                        }else if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb){
                            $cust = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                            $add = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                            $prov = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                        }

                        if($i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->LogistikLaporan() > 0){
                            $csolog = $i->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->LogistikLaporan();
                        }
                        $cso = 0;
                        $index++;
                        ?>
                    @endif
                @else
                <?php
                $so = "";
                $cso = 0;
                $index++;
                ?>
                @endif
            @endif
        @endif
        @if($i->DetailLogistikPart)
            @if(isset($i->DetailLogistikPart[0]))
                @if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so != "")
                @if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so !== $so)
                    <?php
                    $so = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->so;
                    $po = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->no_po;
                    if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog){
                        $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->instansi;
                        $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->alamat;
                        $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Ekatalog->Provinsi->nama;
                    }else if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa){
                        $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->nama;
                        $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                        $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                    }else if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb){
                        $cust = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->nama;
                        $add = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                        $prov = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                    if($i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->LogistikLaporan() > 0){
                        $csolog = $i->DetailLogistikPart[0]->DetailPesananPart->Pesanan->LogistikLaporan();
                    }
                    $cso = 0;
                    $index++;
                    ?>
                @endif
                @else
                <?php
                $so = "";
                $cso = 0;
                $index++;?>
                @endif
            @endif
        @endif
        @if($cso <= 0)
            <tr>
                <td rowspan="{{$csolog}}">{{$index}}</td>
                <td rowspan="{{$csolog}}">{{$so}}</td>
                <td rowspan="{{$csolog}}">{{$po}}</td>
                <td rowspan="{{$csolog}}">{{$cust}}</td>
                <td rowspan="{{$csolog}}">{{$add}}</td>
                <td rowspan="{{$csolog}}">{{$prov}}</td>
                <td rowspan="{{$rowspan}}">{{$i->nosurat}}</td>
                <td rowspan="{{$rowspan}}">{{$i->tgl_kirim}}</td>
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
                        $cdlog++;
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
                    $cdlog++;
                    ?>
                    @endforeach
                @endif
            <?php
            $cso++
            ?>
        @else
            <tr>
            <td rowspan="{{$rowspan}}">{{$i->nosurat}}</td>
            <td rowspan="{{$rowspan}}">{{$i->tgl_kirim}}</td>
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
                    $cdlog++;
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
                $cdlog++;
                ?>
                @endforeach
            @endif
        @endif
        @endforeach
    </tbody>

</table>
