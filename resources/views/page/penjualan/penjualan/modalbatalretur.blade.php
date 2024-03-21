<!-- Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade modalBatalRetur" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Retur / Batal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card removeshadow">
                    <div class="card-body border-0">
                        <h5 class="card-title pl-2 py-2">
                            <b>PT XXYYZZ</b>
                        </h5>
                        <ul class="fa-ul card-text">
                            <li class="py-2">
                                <span class="fa-li">
                                    <i class="fas fa-address-card fa-fw"></i>
                                </span>
                                Alamat Perusahaan
                            </li>
                            <li class="py-2">
                                <span class="fa-li">
                                    <div class="fas fa-map-marker-alt fa-fw"></div>
                                </span>
                                Nusa Tenggara Barat (NTB)
                            </li>
                        </ul>
                    </div>
                </div>

                @php
                    $dataPaket = [
                        [
                            'paket' => 'ULTRA MIST + TROLLEY ECG + BACKUP POWER',
                            'qty' => 1,
                            'produk' => [
                                [
                                    'nama' => 'ULTRA MIST',
                                    'qty' => 1,
                                    'isnoseri' => true,
                                    'noseri' => [
                                        ['noseri' => '123456'],
                                        ['noseri' => '789012'],
                                        ['noseri' => '345678'],
                                    ],
                                ],
                                [
                                    'nama' => 'TROLLEY ECG',
                                    'qty' => 1,
                                    'isnoseri' => false,
                                ],
                                [
                                    'nama' => 'BACKUP POWER',
                                    'qty' => 1,
                                    'isnoseri' => true,
                                    'noseri' => [
                                        ['noseri' => '345678'],
                                        ['noseri' => '789012'],
                                        ['noseri' => '123456'],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'paket' => 'PROMIST 3',
                            'qty' => 1,
                            'produk' => [['nama' => 'PROMIST 3', 'qty' => 1, 'isnoseri' => false]],
                        ],
                    ];
                @endphp

                <card class="card card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#" class="nav-link active" id="home-tab" data-toggle="tab"
                                    data-target="#home" role="tab" aria-controls="home"
                                    aria-selected="true">Informasi</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#" class="nav-link" id="profile-tab" data-toggle="tab"
                                    data-target="#profile" role="tab" aria-controls="profile"
                                    aria-selected="false">Produk</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row d-flex justify-content-between">
                                    <div class="p-2">
                                        <div class="margin">
                                            <div>
                                                <small class="text-muted">No SO</small>
                                            </div>
                                            <div>
                                                <b>XXXYYYZZZ</b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div>
                                                <small class="text-muted">Status</small>
                                            </div>
                                            <div>
                                                <div class="align-center">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-light" role="progressbar"
                                                            aria-valuenow="0" style="width: 100%" aria-valuemin="0"
                                                            aria-valuemax="100">0%</div>
                                                    </div>
                                                    <small class="text-muted">Selesai</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div><b>
                                                    PO-xxyyzz
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small></div>
                                            <div><b>
                                                    18-03-2024
                                                </b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No DO</small></div>
                                            <div><b>
                                                    <em class="text-muted">Belum Tersedia</em>
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal DO</small></div>
                                            <div><b>
                                                    <em class="text-muted">Belum Tersedia</em>
                                                </b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Qty</th>
                                                            <th style="width: 30%">Jumlah Batal / Retur</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dataPaket as $index => $paket)
                                                            <tr>
                                                                <td class="text-left" colspan="100%">
                                                                    {{ $paket['paket'] }}</td>
                                                            </tr>
                                                            <tr
                                                                @foreach ($paket['produk'] as $produk)>
                                                
                                                    <td class="text-left">{{ $produk['nama'] }}</td>
                                                    <td>{{ $produk['qty'] }}</td>
                                                    <td>
                                                        @if (!$produk['isnoseri'])
                                                            <input type="number" class="form-control" placeholder="Masukkan jumlah produk batal / retur">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($produk['isnoseri'] && isset($produk['noseri']))
                                                            @php
                                                                    $noseriValues = [];
                                                                    foreach ($produk['noseri'] as $noseriItem) {
                                                                        $noseriValues[] = $noseriItem['noseri'];
                                                                    }
                                                                    $concatenatedNoseri = implode(',', $noseriValues);
                                                                @endphp
                                                                <button type="button" class="btn btn-outline-primary pilihNoSeri" data-noseri="{{ $concatenatedNoseri }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endif
                                                                </td>
                                                            </tr @endforeach>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col hideNo">
                                        <table class="table tableNoSeri">
                                            <thead>
                                                <tr>
                                                    <th>Nomor Seri</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </card>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.pilihNoSeri', function() {
        var noseri = $(this).data('noseri');
        var noseriArray = noseri.split(',');
        var noseriTable = $('.tableNoSeri tbody');
        noseriTable.empty();
        noseriArray.forEach(function(noseriItem) {
            noseriTable.append(`
                <tr>
                    <td>${noseriItem}</td>
                    <td>
                        <input type="checkbox" name="check_all_noseri" value="${noseriItem}">
                    </td>
                </tr>
            `);
        });
    });
</script>
