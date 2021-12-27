<template>
    <div class="ml-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <nav>
                        <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Kalender</a>
                            <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                aria-selected="false">Tabel</a>
                        </div>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <Calendar></Calendar>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-layer-group"></i> Perakitan Bulan
                            Januari
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered" id="table_produk_perakitan">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="2" class="text-center">Tanggal</th>
                                            <th rowspan="2">Produk</th>
                                            <th rowspan="2">Jumlah Rakit</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="produk" role="tabpanel" aria-labelledby="produk-tab">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-cog"></i> Perakitan Produk Bulan Januari</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Calendar from './Components/Calendar.vue'
    export default {
        components: {
            Calendar
        },
    }
</script>