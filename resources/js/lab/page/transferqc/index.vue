<script>
import Header from "../../components/header.vue";
import Lolos from "./lolos";
import TidakLolos from "./tidaklolos";
export default {
  components: {
    Header,
    Lolos,
    TidakLolos,
  },
  data() {
    return {
      title: "Transfer Produk Berdasarkan SO",
      breadcumbs: [
        {
          name: "Home",
          link: "/",
        },
        {
          name: "Transfer",
          link: "/qc/transfer",
        },
      ],
    };
  },
};
</script>
<template>
  <div>
    <Header :title="title" :breadcumbs="breadcumbs" />
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a
              class="nav-link active"
              id="pills-home-tab"
              data-toggle="pill"
              data-target="#pills-home"
              type="button"
              role="tab"
              aria-controls="pills-home"
              aria-selected="true"
            >
              Lolos
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              id="pills-profile-tab"
              data-toggle="pill"
              data-target="#pills-profile"
              type="button"
              role="tab"
              aria-controls="pills-profile"
              aria-selected="false"
            >
              Tidak Lolos
            </a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div
            class="tab-pane fade show active"
            id="pills-home"
            role="tabpanel"
            aria-labelledby="pills-home-tab"
          >
            <Lolos />
          </div>
          <div
            class="tab-pane fade"
            id="pills-profile"
            role="tabpanel"
            aria-labelledby="pills-profile-tab"
          >
            <TidakLolos />
          </div>
        </div>
      </div>
      <div class="card-body" v-if="$store.state.loading">
        <div class="d-flex justify-content-center">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
