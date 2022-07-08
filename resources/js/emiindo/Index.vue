<template>
    <div>
        <p>{{ akn }}</p>
        <p v-if="loading">loading</p>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    data() {
        return {
            loading: true,
            akn: '',
        }
    },
    methods: {
        async loadData() {
            try {
                // await axios.post('https://sinko.api.hyperdatasystem.com/api/login',{
                //     username: 'superuser.api',
                //     password: 'password'
                // }).then(res => {
                //     this.akn = res;
                //     this.loading = false;
                // });
                await axios.post('https://api.elitech.id/api/login',{
                    username: 'norapenj01',
                    password: '12345678'
                }).then(res => {
                    localStorage.setItem('token_test', res.data.token);
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
    created() {
        this.loadData();
    }
}
</script>