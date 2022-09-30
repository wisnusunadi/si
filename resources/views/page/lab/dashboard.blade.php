@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" integrity="sha256-+8RZJua0aEWg+QVVKg4LEzEEm/8RFez5Tb4JBNiV5xA=" crossorigin="anonymous"></script>
<div class="container-fluid">

    <div class="container p-3 bg-white">

        <!-- card Alat uji & peminjaman -->
        <div class="row">
            <div class="col-4 p-1">
                <div class="card mb-1">
                    <div class="mx-2 mt-2"><strong>Alat Uji</strong></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <!-- col kiri alat uji -->
                            <!-- info total -->
                            <div class="col p-1">
                                <div class="card shadow-none border-0 m-0 bc-primary text-primary">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 p-1">
                                                <div class="card-title fs-4"><strong>{{ json_decode($data)->total }}</strong></div>
                                                <div class="card-text">Total</div>
                                            </div>
                                            <div class="col-6 p-1">
                                                <div class="card-title fs-4"><strong class="text-end text-end">{{ json_decode($data)->tersedia }}</strong></div>
                                                <div class="card-text text-end">Tersedia</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <!-- info total end -->

                            <!-- info not ok -->
                            <div class="col p-1">
                                <div class="card shadow-none border-0 m-0 bc-danger text-danger">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-title fs-2"><strong>{{ json_decode($data)->not }}</strong></div>
                                            </div>
                                            <div class="col d-flex justify-content-center">
                                                <i class="fa-regular fa-circle-xmark fa-3x" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="card-text">Not OK</div>
                                    </div>
                                </div>    
                            </div>
                            <!-- info not ok end -->
                            <!-- col kiri alat uji end -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-8 p-1">
                <div class="card mb-1">
                <div class="mt-2 mx-2"><strong>Peminjaman</strong></div>
                    <div class="card-body pt-0 m-0">
                        <div class="row">
                        <!-- info permintaan pinjam -->
                        <div class="col p-1">
                            <div class="card shadow-none border-0 m-0 bc-warning text-warning">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-title fs-2"><strong>{{ json_decode($data)->permintaan }}</strong></div>
                                        </div>
                                        <div class="col d-flex justify-content-center">
                                            <i class="fa-solid fa-hand-holding fa-3x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="card-text">Perminataan Pijam</div>
                                </div>
                            </div>    
                        </div>
                        <!-- info permintaan pinjam end -->

                        <!-- info Dipinjam Pengembalian -->
                        <div class="col p-1">
                            <div class="card shadow-none border-0 m-0 bc-success text-success">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-title fs-2"><strong>{{ json_decode($data)->dipinjam }}</strong></div>
                                        </div>
                                        <div class="col d-flex justify-content-center">
                                            <i class="fa-regular fa-clock fa-3x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="card-text">Dipinjam</div>
                                </div>
                            </div>    
                        </div>
                        <!-- info Dipinjam Pengembalian end -->

                        <!-- info batas pengembalian -->
                        <div class="col p-1">
                            <div class="card shadow-none border-0 m-0 bc-danger text-danger">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-title fs-2"><strong>{{ json_decode($data)->batasPinjam }}</strong></div>
                                        </div>
                                        <div class="col d-flex justify-content-center">
                                            <i class="fa-solid fa-circle-exclamation fa-3x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="card-text">Melebihi Batas</div>
                                </div>
                            </div>    
                        </div>
                        <!-- info batas pengembalian end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card Alat uji & peminjaman end -->

        <div class="row">
        <!-- card External -->
            <div class="col-3 p-1">
                <div class="card mb-1">
                    <div class="mx-2 mt-2"><strong>Perbaikan & Kalibrasi</strong></div>
                    <div class="card-body pt-0">
                        <!-- card sedang maintenence diluar -->
                        <div class="col p-1">
                            <div class="card shadow-none border-0 m-0 bc-primary text-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-title fs-2"><strong>{{ json_decode($data)->external }}</strong></div>
                                        </div>
                                        <div class="col d-flex justify-content-center">
                                            <i class="fas fa-external-link-alt fa-2x pt-3"></i>
                                        </div>
                                    </div>
                                    <div class="card-text">External</div>
                                </div>
                            </div>    
                        </div>
                        <!-- card sedang maintenence diluar end -->
                    </div>
                </div>
            </div>
        <!-- card External end -->

        <!-- card Verifikasi Perawatan -->
            <div class="col">
                <div class="row">
                    <div class="col p-1">
                        <div class="card mb-1">
                            <div class="mx-2 mt-2"><strong>Perawatan & Verifikasi</strong></div>
                                <div class="card-body pt-0">

                                <div class="row">
                                    <!-- card jadwal maintenence bulan ini -->
                                    <div class="col p-1">
                                        <div class="card shadow-none border-0 m-0 bc-warning text-warning">
                                            <div class="card-body py-2">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="card-title fs-2 m-0"><strong>{{ json_decode($data)->verifikasiNow }}</strong></div>
                                                        <div class="card-text">Verifikasi</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-title fs-2 m-0"><strong>{{ json_decode($data)->perawatanNow }}</strong></div>
                                                        <div class="card-text">Perawatan</div>
                                                    </div>
                                                    <div class="col d-flex justify-content-center">
                                                        <i class="fa-solid fa-calendar fa-3x pt-3" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">Jadwal Bulan Ini</div>
                                            </div>
                                        </div>    
                                    </div>
                                    <!-- card jadwal maintenence bulan ini end -->

                                    <!-- card jadwal maintenence terlewati -->
                                    <div class="col p-1">
                                        <div class="card shadow-none border-0 m-0 bc-danger text-danger">
                                            <div class="card-body py-2">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="card-title fs-2 m-0"><strong>{{ json_decode($data)->verifikasiLebih }}</strong></div>
                                                        <div class="card-text">Verifikasi</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-title fs-2 m-0"><strong>{{ json_decode($data)->perawatanLebih }}</strong></div>
                                                        <div class="card-text">Perawatan</div>
                                                    </div>
                                                    <div class="col d-flex justify-content-center">
                                                        <i class="fa-solid fa-calendar-xmark fa-3x pt-3" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">Jadwal Terlewati</div>
                                            </div>
                                        </div>    
                                    </div>
                                    <!-- card jadwal maintenence terlewati end -->

                                    <!-- card alat uji yang belum memiliki data perawatan verifikasi -->
                                    <!-- <div class="col-3 p-1">
                                        <div class="card shadow-none border-0 m-0 bc-secondary text-secondary">
                                            <div class="card-body py-2">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="card-title fs-2 m-0"><strong>10</strong></div>
                                                    </div>
                                                    <div class="col d-flex justify-content-center">
                                                        <i class="fa-solid fa-circle-question fa-3x pt-3"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">Belum Terdata</div>
                                            </div>
                                        </div>    
                                    </div> -->
                                    <!-- card alat uji yang belum memiliki data perawatan verifikasi end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- card maintenance end -->
        </div>

        <!-- bagian tabel peminjaman alat uji -->
        <div class="row">
            <!-- col pemijaman alat uji -->
            <div class="col-6 p-1">
                <div class="card mb-1">
                    <div class="mt-2 mx-2"><strong>Peminjaman Alat Uji</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-striped table-sm" id="tablePengajuan">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Peminjam</th>
                                    <th>Nama Alat</th>
                                    <th>Serial NM</th>
                                    <th>Kondisi</th>
                                    <th>Tgl Batas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col peminjaman alat uji end -->

            <!-- col pengembalian alatuji -->
            <div class="col-6 p-1">
                <div class="card">
                    <div class="mt-2 mx-2"><strong>Pengembalian Alat Uji</strong></div>
                    <div class="card-body">

                        <div class="table-responsive">
                        <table class="table table-striped table-sm" id="tableDipinjam">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Peminjam</th>
                                    <th scope="col">Alat Uji</th>
                                    <th scope="col">Serial Num</th>
                                    <th scope="col">Kondisi</th>
                                    <th scope="col">Batas</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- col pengembalian alatuji end -->
        </div>
        <!-- bagian peminjaman alat uji end -->

        <!-- bagian grafik -->
        <div class="row">
            <!-- card grafik peminjaman alat uji -->
            <div class="col p-1">
                <div class="card mb-1">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
            <!-- card grafik peminjaman alat uji end -->

            <!-- card gafik target maintenance alat uji -->
            <!-- <div class="col p-1">
                <div class="card mb-1">
                    <div class="card-body">

                    </div>
                </div>
            </div> -->
            <!-- card gafik target maintenance alat uji end -->
        </div>
        <!-- bagian grafik end -->

    </div>
</div>
@stop
@section('adminlte_js')
<script>
    var tablePeminjaman = $('#tablePengajuan').DataTable({
        processing: false,
        serverSide: false,
        destroy: false,
        ajax: "{{ url('/api/inventory/data_dashboard_permintaan') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'nm_alatuji', name: 'nama_alat'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'kondisi_id', name: 'kondisi_awal'},
            {data: 'tgl_batas', name: 'tgl_batas'},
            {data: 'aksi', name: 'aksi'}
        ],
        order:[[5, 'desc']]
    });

    var tableDipinjam = $('#tableDipinjam').DataTable({
        processing: false,
        serverSide: false,
        destroy: false,
        ajax: "{{ url('/api/inventory/data_dashboard_pengembalian') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'nm_alatuji', name: 'nama_alat'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'kondisi_awal', name: 'kondisi_awal'},
            {data: 'tgl_batas', name: 'tgl_batas'},
            {data: 'aksi', name: 'aksi'}
        ],
        //responsive: true
    });

    var pinjamBulananChart = new Chart(document.getElementById('myChart').getContext('2d'),{
        type: 'line',
        data: {
            labels: ['jan', 'feb', 'mar', 'april', 'mei', 'juni', 'juli', 'agust', 'sep', 'okt', 'nov', 'des'],
            datasets: [{
                data: [
                    @for($i=0;$i<=11;$i++)
                        {{ json_decode($data)->total_peminjaman[$i] != null ? json_decode($data)->total_peminjaman[$i].',' : '0,' }}
                    @endfor
                ],
                label: 'Dipinjam',
                borderColor: "#3e95cd",
                fill: false
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Jumlah Peminjaman Perbulan'
                },
                legend: { 
                    display: false
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
</script>

@endsection