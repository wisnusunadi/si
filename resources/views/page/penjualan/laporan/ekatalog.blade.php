<table class="table">
    <thead style="text-align: center;">
        <tr>
            <th colspan="18">
                Laporan Penjualan Ekatalog
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No AKN</th>
            <th>No PO</th>
            <th>No SJ</th>
            <th>Customer / Distributor</th>
            <th>Batas Kontrak</th>
            <th>Tanggal Pengiriman</th>
            <th>Tanggal PO</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Produk</th>
            <th>No Seri</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ekatalog as $i)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$i->no_paket}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>