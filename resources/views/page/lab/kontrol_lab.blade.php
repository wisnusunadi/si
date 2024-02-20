<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>No Order</th>
            <th>Tgl Masuk</th>
            <th>Nama Alat</th>
            <th>Type</th>
            <th>No Seri</th>
            <th>Nama Pemilik</th>
            <th>Nama Pemilik Sertifikat</th>
            <th>Alamat</th>
            <th>Tgl Kalibrasi</th>
            <th>Teknisi</th>
            <th>No Sertifikat</th>
            <th>No SJ</th>
            <th>Nama Distributor</th>
            <th>No PO</th>
            <th>Status</th>
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
                <th>{{ $d['status'] == 'ok' ? 'Lolos Kalibrasi' : 'Tidak Lolos Kalibrasi' }}</th>
            </tr>
        @endforeach
    </tbody>
