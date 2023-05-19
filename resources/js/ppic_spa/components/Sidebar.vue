<template>
  <aside class="aside is-placed-left is-expanded is-dark">
    <div class="aside-tools">
      <div class="aside-tools-label">
        <router-link
          style="
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
          "
          to="/ppic"
          class=""
        >
          <strong class="has-text-centered">Sinko Prima Alloy</strong>
        </router-link>
      </div>
    </div>
    <div class="menu is-menu-main">
      <div v-for="item in sidebar" :key="item.header">
        <p class="menu-label">{{ item.header }}</p>
        <ul class="menu-list">
          <li v-for="menu in item.menu" :key="menu.text">
            <template v-if="menu.url !== undefined">
              <router-link :to="menu.url" class="has-icon">
                <span class="icon"><i :class="menu.icon"></i></span>
                <span class="menu-item-label">{{ menu.text }}</span>
              </router-link>
            </template>
            <template v-else>
              <a class="has-icon has-dropdown-icon">
                <span class="icon"><i :class="menu.icon"></i></span>
                <span class="menu-item-label">{{ menu.text }}</span>
                <div class="dropdown-icon">
                  <span class="icon"><i class="fas fa-plus"></i></span>
                </div>
              </a>
              <ul>
                <li v-for="(submenu, index) in menu.submenu" :key="index">
                  <router-link :to="submenu.url">
                    <span>{{ submenu.text }}</span>
                  </router-link>
                </li>
              </ul>
            </template>
          </li>
        </ul>
      </div>
      <ul class="menu-list">
        <li>
            <a href="https://docs.google.com/spreadsheets/d/1OxZY8JTqDBrIm89A9cTcTnRndRBOUCtnblY0f0F76p4/edit?usp=sharing">
              <span class="icon"><i class="fas fa-chart-line"></i></span>
              <span class="menu-item-label">Monitoring Lap. Teknis</span>
            </a>
          </li>
      </ul>
    </div>
  </aside>
</template>

<script>
import ppic_sidebar from "../config/sidebar.json";
import manager_sidebar from "../../manager_spa/config/sidebar.json";

export default {
  name: "Sidebar",

  beforeCreate() {
    var element = document.getElementsByTagName("html");
    element[0].classList.add(
      "has-aside-left",
      "has-aside-mobile-transition",
      "has-navbar-fixed-top",
      "has-aside-expanded"
    );
  },

  computed: {
    sidebar() {
      if (this.$store.state.user.divisi_id === 24) return ppic_sidebar;
      else if (this.$store.state.user.divisi_id === 3) return manager_sidebar;
    },
  },

  updated() {
    Array.from(document.getElementsByClassName("menu is-menu-main")).forEach(
      function (el) {
        Array.from(el.getElementsByClassName("has-dropdown-icon")).forEach(
          function (elA) {
            elA.addEventListener("click", function (e) {
              var dropdownIcon = e.currentTarget
                .getElementsByClassName("dropdown-icon")[0]
                .getElementsByClassName("fas")[0];
              e.currentTarget.parentNode.classList.toggle("is-active");
              dropdownIcon.classList.toggle("fa-plus");
              dropdownIcon.classList.toggle("fa-minus");
            });
          }
        );
      }
    );
  },
};
</script>

<style lang="scss">
/* sidebar */
@media screen and (min-width: 1024px) {
  html.has-aside-left.has-aside-expanded nav.navbar,
  html.has-aside-left.has-aside-expanded body {
    padding-left: 16rem;
  }
  html.has-aside-left nav.navbar,
  html.has-aside-left body {
    transition: padding-left 250ms ease-in-out 50ms;
  }
  html.has-aside-left aside.is-placed-left {
    display: block;
  }
  aside.aside.is-expanded {
    width: 16rem;
  }
  aside.aside.is-expanded .menu-list .icon {
    width: 3rem;
  }
  aside.aside.is-expanded .menu-list .icon.has-update-mark:after {
    right: 0.65rem;
  }
  aside.aside.is-expanded .menu-list span.menu-item-label {
    display: inline-block;
  }
  aside.aside.is-expanded .menu-list li.is-active ul {
    display: block;
  }
}

aside.aside {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 40;
  height: 100vh;
  padding: 0;
  box-shadow: none;
  background: #363636;
}

aside.aside .aside-tools {
  display: flex;
  justify-content: center;
  flex-direction: row;
  width: 100%;
  background-color: #4a4a4a;
  color: white;
  line-height: 3.25rem;
  height: 3.25rem;
  padding-left: 0.75rem;
  flex: 1;
  font-size: 1.5rem;
}

aside.aside .aside-tools .icon {
  margin-right: 0.75rem;
}

aside.aside .menu-list li a.has-dropdown-icon {
  position: relative;
  padding-right: 3rem;
}

aside.aside .menu-list li a.has-dropdown-icon .dropdown-icon {
  position: absolute;
  top: 0.5rem;
  right: 0;
}

aside.aside .menu-list li ul {
  display: none;
  border-left: 0;
  background-color: #282c33;
  padding-left: 0;
  margin: 0 0 0.75rem;
}

aside.aside .menu-list li ul li a {
  padding: 0.75rem 0 0.75rem 0.75rem;
  font-size: 0.95rem;
}

aside.aside .menu-list li ul li a.has-icon {
  padding-left: 0;
}

aside.aside .menu-list li ul li a.is-active:not(:hover) {
  background: transparent;
}

aside.aside .menu-label {
  padding: 0 0.75rem;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}

@media screen and (max-width: 1023px) {
  #app,
  nav.navbar {
    transition: margin-left 250ms ease-in-out 50ms;
  }
  aside.aside {
    transition: left 250ms ease-in-out 50ms;
  }
  html.has-aside-mobile-transition body {
    overflow-x: hidden;
  }
  html.has-aside-mobile-transition body,
  html.has-aside-mobile-transition #app,
  html.has-aside-mobile-transition nav.navbar {
    width: 100vw;
  }
  html.has-aside-mobile-transition aside.aside {
    width: 15rem;
    display: block;
    left: -15rem;
  }
  html.has-aside-mobile-transition aside.aside .image img {
    max-width: 4.95rem;
  }
  html.has-aside-mobile-transition aside.aside .menu-list li.is-active ul {
    display: block;
  }
  html.has-aside-mobile-transition aside.aside .menu-list a .icon {
    width: 3rem;
  }
  html.has-aside-mobile-transition
    aside.aside
    .menu-list
    a
    .icon.has-update-mark:after {
    right: 0.65rem;
  }
  html.has-aside-mobile-transition
    aside.aside
    .menu-list
    a
    span.menu-item-label {
    display: inline-block;
  }
  html.has-aside-mobile-expanded #app,
  html.has-aside-mobile-expanded nav.navbar {
    margin-left: 15rem;
  }
  html.has-aside-mobile-expanded aside.aside {
    left: 0;
  }
}
/* sidebar */
</style>