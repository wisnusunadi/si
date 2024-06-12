<script>
import axios from 'axios';
import uploadFile from '../../components/uploadFile.vue';
export default {
    components: {
        uploadFile
    },
    data() {
        return {
            file: []
        }
    },
    methods: {
        submit() {
            // array of files
            let formData = new FormData();

            for (let i = 0; i < this.file.length; i++) {
                formData.append('file[]', this.file[i]);
            }

            axios.post('/api/hr/meet/hasil/dokumen_ftp', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
            });
        },
        inilink() {
            window.open('sftp://wisnu.elitech:wisnus1nko@192.168.13.12/File%Server/IT/ERP_Storage/56d50364-f4be-485e-8021-df35938406d6.mp4')
        }
    },
}
</script>
<template>
    <div>
        <uploadFile @changed="file = $event" />
        <button class="btn btn-primary" @click="submit">Submit</button>
    </div>
</template>