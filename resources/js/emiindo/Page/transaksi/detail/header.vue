<script>
export default {
    props: ['detail']
}
</script>
<template>
    <div>
        <div class="card border-light cardremoveshadow">
            <div class="card-body">
                <h5 class="pl-2 py-2">
                    <b>{{ detail?.nama_customer }}</b>
                </h5>
                <ul class="fa-ul card-text" v-if="detail.jenis == 'ekatalog'">
                    <li class="py-2">
                        <span class="fa-li"><span class="far fa-building fa-fw"></span></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Instansi</div>
                            <div class="col-lg-11 col-md-10">
                                {{ detail?.instansi }}
                            </div>
                        </div>
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-user-alt fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Satuan</div>
                            <div class="col-lg-11 col-md-10">
                                {{ detail?.satuan }}
                            </div>
                        </div>
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Alamat</div>
                            <div class="col-lg-11 col-md-10">
                                {{ detail?.alamat }}
                            </div>
                        </div>

                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Provinsi</div>
                            <div class="col-lg-11 col-md-10">
                                <em class="text-muted" v-if="!detail?.provinsi">Belum Tersedia</em>
                                {{ detail?.provinsi?.nama }}
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="fa-ul card-text" v-else>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                        {{ detail?.customer?.alamat }}
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        {{ detail?.provinsi }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="alert alert-danger" role="alert" v-if="detail.jenis == 'ekatalog' && detail?.ket">
            <i class="fas fa-exclamation-triangle"></i> <strong>Keterangan: </strong>{{ detail?.ket }}
        </div>
    </div>
</template>