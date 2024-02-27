<table border="1">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO ORDER</th>
            <th>TGL MASUK</th>
            <th>NAMA ALAT</th>
            <th>TYPE</th>
            <th>NO SERI</th>
            <th>NAMA PEMILIK</th>
            <th>NAMA PEMILIK SERTIFIKAT</th>
            <th>ALAMAT</th>
            <th>TGL KALIBRASI</th>
            <th>TEKNISI</th>
            <th>NOMER SERTIFIKAT</th>
            <th>No SJ / NO. BAST</th>
            <th>NAMA DISTRIBUTOR</th>
            <th>NO. PO / NO. E-CAT</th>
            <th>TGL PENYERAHAN</th>
            <th>KETERANGAN</th>
            <th>TANGGAL KIRIM</th>
            <th>DICETAK</th>
            <th>HASIL</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($data as $d)
            <tr>
                <th>{{ $d['no'] }}</th>
                <th>{{ $d['no_order'] }}</th>
                <th>{{ $d['tgl_masuk'] }}</th>
                <th>{{ $d['nama_alat'] }}</th>
                <th>{{ $d['type'] }}</th>
                <th>{{ $d['noseri'] }}</th>
                <th>{{ $d['nama_pemilik'] }}</th>
                <th>{{ $d['nama_pemilik_sert'] }}</th>
                <th>{{ $d['alamat'] }}</th>
                <th>{{ $d['tgl_kalibrasi'] }}</th>
                <th>{{ $d['pemeriksa'] }}</th>
                <th>{{ $d['no_sertifikat'] }}</th>
                <th>{{ $d['nosj'] }}</th>
                <th>{{ $d['info']->nama }}</th>
                <th>{{ $d['no_po'] }}{{ $d['info']->no_paket != '' ? '/' . $d['info']->no_paket : '' }}</th>
                <th>{{ $d['tgl_serah'] }}</th>
                <th>{{ $d['keterangan'] }}</th>
                <th>{{ $d['tgl_kirim'] }}</th>
                <th>{{ $d['dicetak'] }}</th>
                <th>{{ $d['status'] == 'ok' ? 'Lolos Kalibrasi' : 'Tidak Lolos Kalibrasi' }}</th>
            </tr>
        @endforeach
    </tbody>
