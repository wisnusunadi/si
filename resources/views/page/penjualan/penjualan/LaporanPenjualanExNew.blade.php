<style>
    .va {
        vertical-align: bottom;
    }
</style>
@if ($seri == 'kosong')
    <table border="1">
        <thead>
            <tr>
                <th colspan="22" style="text-align:center">
                    {{ $header }}
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>No SxO</th>
                <th>No PO</th>
                <th>Tanggal PO</th>
                <th>Surat Jalan</th>
                <th>Tgl Surat Jalan</th>
                <th>No Urut</th>
                <th>No AKN</th>
                <th>Customer / Distributor</th>
                <th>Instansi</th>
                <th>Satuan</th>
                <th>Tanggal Pesan</th>
                <th>Batas Kontrak</th>
                <th>Produk</th>
                <th>Produk(E-Purchasing)</th>
                <th>Detail Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Ongkir</th>
                <th>Subtotal</th>
                <th>Status Penjualan</th>
                <th>Status AKN</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
</table>
@endif
