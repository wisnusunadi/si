<template>
    <div>
        
    </div>
</template>

<script>
import axios from 'axios';
export default {
    methods: {
        async login(){
            let data = {
                username: "superuser.api",
                password: "password"
            }
            await axios.post('https://sinko.api.hyperdatasystem.com/api/login', data).then(response => {
                sessionStorage.setItem('token', response.data.token);
            }).catch(error => {
                console.log(error);
            })
        }
    },
    mounted() {
        // this.login();
    }
}
</script>