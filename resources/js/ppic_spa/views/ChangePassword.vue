<script>
import axios from "axios";
import $ from "jquery";

export default {
    data() {
        return {
            token: localStorage.getItem('lokal_token'),
            form: {
                user_id: '',
                pwd_lama: '',
                password: '',
                password_confirmation: '',
            }
        }
    },
    computed: {
        getUserId() {
            return this.$store.state.user.id;
        }
    },
    watch: {
        getUserId() {
            this.form.user_id = this.getUserId;
        }
    },
    methods: {
        async submit() {
            try {
                const { data } = await axios.post("/api/ppic/update_pwd", this.form, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
            switch (data.data) {
                case 'success':
                this.$swal('Success', 'Password berhasil diubah', 'success');
                    break;
            
                case 'invalid':
                this.$swal('Error', 'Password lama salah', 'error');
                    break;

                case 'same':
                this.$swal('Error', 'Password baru tidak boleh sama dengan password lama', 'error');
                    break;

                default:
                this.$swal('Error', 'Cek Form', 'error');
                    break;
            }
            } catch (error) {
                this.$swal('Error', `${error}`, 'error');   
            }
        }
    }
}
</script>
<template>
    <div class="card">
        <header class="card-header">
    <p class="card-header-title">
      Ubah Password
    </p>
  </header>
  <div class="card-content">
    <div class="is-align-content-center">
        <div class="field">
            <label class="label">Password Lama</label>
            <div class="control">
                <input class="input" v-model="form.pwd_lama" type="password" placeholder="Password Lama">
            </div>
        </div>
        <div class="field">
            <label class="label">Password Baru</label>
            <div class="control">
                <input class="input" v-model="form.password" type="password" placeholder="Password Baru">
            </div>
        </div>
        <div class="field">
            <label class="label">Konfirmasi Password Baru</label>
            <div class="control">
                <input class="input" v-model="form.password_confirmation" type="password" placeholder="Konfirmasi Password Baru">
            </div>
        </div>
    </div>
  </div>
  <footer class="card-footer">
    <div class="card-footer-item is-flex is-justify-content-flex-end">
            <button class="button is-primary" @click="submit">Submit</button>
        </div>
  </footer>
</div>
</template>