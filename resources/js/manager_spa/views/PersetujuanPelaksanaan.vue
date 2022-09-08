<template>
  <div>
    <h1 class="title">Persetujuan Pelaksanaan Jadwal</h1>
    <Persetujuan />
  </div>
</template>

<script>
import axios from "axios";
import Persetujuan from "./Persetujuan.vue";

export default {
  components: {
    Persetujuan,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios
        .get("/api/ppic/data/perakitan/pelaksanaan",{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            }
        }, {
          params: {
            konfirmasi: "0",
          },
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          if (response.data.length == 0)
            this.$store.commit("setStatus", "penyusunan");
        });
      this.$store.commit("setIsLoading", false);
    },

    checkToken(){
        if(localStorage.getItem('lokal_token') == null){
            // event.preventDefault();
            this.$swal({
                title: 'Session Expired',
                text: 'Silahkan login kembali',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    this.logout()
                }
            })
        }
    },

    async logout() {
      await axios.post("/logout");
      document.location.href = "/";
    },
  },

  created() {
        this.checkToken();
    },

  mounted() {
    this.loadData();
  },

  computed: {
    notif() {
      return this.$store.state.notif;
    },
  },

  watch: {
    notif() {
      if (this.$store.state.notif.user.divisi_id === 24) this.loadData();
    },
  },
};
</script>
