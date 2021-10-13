<template>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="margin-bottom:5px;">
                        <div class="col-lg-12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <table class="table" style="text-align:center;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No Penjualan</th>
                                        <th>Batas Pengiriman</th>
                                        <th>Nomor PO</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>28-09-2021</td>
                                        <td>AKN1-48915718957</td>
                                        <td><span style="color:red;"><b>15-10-2021</b></span></td>
                                        <td>0003/PO/ON/10/21</td>
                                        <td>-</td>
                                        <td><span class="badge yellow-text">Sebagian diinput</span></td>
                                        <td><div class="dropdown-toggle" data-toggle="dropdown"  id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <router-link :to="{ path: '/gbj/penjualan/detail/3' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Detail</button></router-link>
                                            </div></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>23-09-2021</td>
                                        <td>AKN1-48915718940</td>
                                        <td><span style="color:#FFC700;"><b>17-10-2021</b></span></td>
                                        <td>0002/PO/ON/10/21</td>
                                        <td>-</td>
                                        <td><span class="badge red-text">Belum diinput</span></td>
                                        <td>
                                            <div class="dropdown-toggle" data-toggle="dropdown"  id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <router-link :to="{ path: '/gbj/penjualan/detail/2' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Detail</button></router-link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>21-09-2021</td>
                                        <td>AKN1-48915718938</td>
                                        <td><span>13-10-2021</span></td>
                                        <td>0001/PO/ON/10/21</td>
                                        <td>-</td>
                                        <td><span class="badge green-text">Selesai</span></td>
                                        <td><div class="dropdown-toggle" data-toggle="dropdown"  id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <router-link :to="{ path: '/gbj/penjualan/detail/1' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Detail</button></router-link>
                                            </div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>