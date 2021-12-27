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
            padding-top: 5px;
          "
          to="/ppic"
          class=""
        >
          <img
            class="image"
            style="width: 50%"
            src="/assets/image/logo/sinko.png"
            alt=""
          />
        </router-link>
      </div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">General</p>
      <ul class="menu-list">
        <li>
          <router-link to="/ppic" class="router-link-active has-icon">
            <span class="icon"><i class="fas fa-home"></i></span>
            <span class="menu-item-label">Dashboard</span>
          </router-link>
        </li>
      </ul>
      <p class="menu-label">Data</p>
      <ul class="menu-list">
        <li>
          <a class="has-icon has-dropdown-icon">
            <span class="icon"><i class="fas fa-boxes"></i></span>
            <span class="menu-item-label">Stok</span>
            <div class="dropdown-icon">
              <span class="icon"><i class="fas fa-plus"></i></span>
            </div>
          </a>
          <ul>
            <li>
              <router-link to="/ppic/gbj">
                <span>GBJ</span>
              </router-link>
            </li>
            <li>
              <router-link to="/ppic/gk">
                <span>GK</span>
              </router-link>
            </li>
          </ul>
        </li>
        <li>
          <router-link to="/ppic/perakitan" class="has-icon">
            <span class="icon has-update-mark"
              ><i class="fas fa-table"></i
            ></span>
            <span class="menu-item-label">Perakitan</span>
          </router-link>
        </li>
        <li>
          <router-link to="/ppic/so" class="has-icon">
            <span class="icon"><i class="fas fa-database"></i></span>
            <span class="menu-item-label">Sales Order</span>
          </router-link>
        </li>
      </ul>
      <p class="menu-label">Fitur</p>
      <ul class="menu-list">
        <li>
          <a class="has-icon has-dropdown-icon">
            <span class="icon"><i class="fas fa-calendar-alt"></i></span>
            <span class="menu-item-label">Jadwal Perakitan</span>
            <div class="dropdown-icon">
              <span class="icon"><i class="fas fa-plus"></i></span>
            </div>
          </a>
          <ul>
            <li>
              <router-link to="/ppic/jadwal_perencanaan">
                <span>Perencanaan</span>
              </router-link>
            </li>
            <li>
              <router-link to="/ppic/jadwal_pelaksanaan">
                <span>Pelaksanaan</span>
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
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

  mounted() {
    let this_obj = this;
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