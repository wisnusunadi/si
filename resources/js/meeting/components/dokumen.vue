<script>
export default {
    props: ["jenis", "link", "nama"],
    methods: {
        openDokumen(link) {
            window.open(link, "_blank");
        },
        isPDF(name) {
            return name.split('.').pop().toLowerCase() === 'pdf';
        },
    },
};
</script>
<template>
    <div>
        <!-- image -->
        <img :src="`/api/hr/meet/show_dokumen_ftp?name=${nama}`" v-if="jenis == 'foto'" width="500px" class="mt-4"
            :alt="nama" />
        <!-- video -->
        <video v-if="jenis == 'video'" width="500px" controls>
            <source :src="`/api/hr/meet/show_dokumen_ftp?name=${nama}`" />
            <p>Your browser doesn't support HTML5 video. Here is a <a :href="`/api/hr/meet/show_dokumen_ftp?name=${nama}`">link to the video</a> instead.</p>
        </video>
        <!-- video link -->
        <!-- <iframe :src="link" v-if="jenis == 'video'" width="100%" height="315" frameborder="0" allowfullscreen></iframe> -->
        <!-- rekaman -->
        <audio controls v-if="jenis == 'rekaman'">
            <source :src="`/api/hr/meet/show_dokumen_ftp?name=${nama}`" type="audio/mpeg" />
        </audio>
        <!-- dokumen -->
        <div class="card card-hover" v-if="jenis == 'dokumen'" :style="isPDF(nama) ? '' : 'width: 18rem;'">
            <iframe :src="`/api/hr/meet/show_dokumen_ftp?name=${nama}`" v-if="isPDF(nama)" width="100%" height="315"
                frameborder="0" allowfullscreen></iframe>
            <div class="card-body" @click="openDokumen(`/api/hr/meet/show_dokumen_ftp?name=${nama}`)" v-else>
                <div class="card-text text-center">
                    <p class="text-secondary">{{ nama }}</p>
                    <div class="d-flex justify-content-center">
                        <i class="fa fa-download py-2" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<style>
.card-hover {
    /* Card styling */
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;

    /* Apply the hover effect */
    cursor: pointer; /* Change the cursor to a pointer on hover */
    transition: background-color 0.3s ease; /* Smooth transition for background color change */
}

.card-hover:hover {
    background-color: #ddd; /* Change background color on hover */
}
</style>