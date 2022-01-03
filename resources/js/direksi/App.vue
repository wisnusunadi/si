<template>
    <div class="ml-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <nav>
                        <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true" @click="isGrafik = false">Kalender</a>
                            <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                aria-selected="false" @click="isGrafik = false">Tabel</a>
                            <a id="grafik-tab" data-toggle="tab" href="#grafik" role="tab" aria-controls="grafik"
                                aria-selected="false" @click="isGrafik = true">Grafik</a>
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
                            <Calendar/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="col-md-12">
                <Table/>
            </div>
        </div>
        <div class="tab-pane fade" id="grafik" role="tabpanel" aria-labelledby="grafik-tab">
            <div class="col-md-12" v-if="isGrafik">
                <Grafik/>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Calendar from './Components/Calendar.vue'
    import Table from './Components/Table.vue';
    import Grafik from './Components/Grafik.vue';
    export default {
        components: {
            Calendar,
            Table,
            Grafik
        },
        data() {
            return {
                isGrafik: false,
            }
        },
    }
</script>