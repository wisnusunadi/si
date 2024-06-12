<script>
import dokumen from "../../../../components/dokumen.vue";
export default {
    props: ["meeting", "status"],
    components: {
        dokumen,
    },
};
</script>
<template>
    <div id="accordion">
        <div class="card" v-for="(meet, idx) in meeting">
            <div class="card-header" :id="'heading' + idx">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" :data-target="'#collapseOne' + idx"
                        aria-expanded="true" :aria-controls="'collapseOne' + idx">
                        {{ meet.jenis }} ({{ meet.dokumen.length }} file(s))
                    </button>
                </h5>
            </div>

            <div :id="'collapseOne' + idx" class="collapse" :class="{ show: idx == 0 }"
                :aria-labelledby="'heading' + idx" data-parent="#accordion">
                <div class="card-body">
                    <div class="row" v-if="meet.dokumen.length > 0">
                        <div v-for="dokumen in meet.dokumen" class="col-5">
                            <dokumen :jenis="meet.jenis" :nama="dokumen.nama" />
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-center">Tidak ada file</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>