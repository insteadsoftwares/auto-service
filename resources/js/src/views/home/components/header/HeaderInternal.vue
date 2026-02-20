<template>
  <header id="header-3" class="header-area absolate-header">
    <div class="sticky-area">
      <div class="navigation">
        <div class="container">
          <div class="row align-items-center">

            <div class="col-lg-3">
              <div class="logo">
                <router-link class="navbar-brand" :to="{name:'HomePage'}">
                  <img src="/homePage/img/logo-white.png" alt="">
                </router-link>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="main-menu">
                <nav class="navbar navbar-expand-lg">

                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">

                      <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'HomePage' }">Accueil</router-link>
                      </li>
                      <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'ServicesPage' }">Services</router-link>
                      </li>
                      <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'AboutPage' }">À propos</router-link>
                      </li>
					  <li class="nav-item" v-if="isLoggedIn && userRole == 'client'">
						<router-link class="nav-link" :to="{ name: 'ClientSpacePage' }">Espace Client</router-link>
					  </li>
                      <li class="nav-item" v-if="isLoggedIn && userRole == 'technician'">
                        <router-link class="nav-link" :to="{ name: 'GarageSpacePage' }">Espace Garage</router-link>
                      </li>
                      <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'ContactPage' }">Contact</router-link>
                      </li>
                    </ul>
                  </div>
                </nav>
              </div>
            </div>

            <div class="col-lg-3 text-end">
              <div class="header-right-content">
                <router-link :to="{ name: 'ClientSpacePage', query: { tab: 'addAppointment' } }" v-if="(isLoggedIn && userRole == 'client')" class="header-btn main-btn">Prendre RDV</router-link>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { onMounted } from "@vue/composition-api"
import { mapState } from 'vuex'

export default {
  name: "HeaderComponent",
  setup() {
    onMounted(() => {
      $(".navbar-toggler").on("click", function () {
        $(this).toggleClass("active")
      })

      $(".navbar-nav li a").on("click", function () {
        $(".sub-nav-toggler").removeClass("active")
      })

      var subMenu = $(".navbar-nav .sub-menu")

      if (subMenu.length) {
        subMenu
          .parent("li")
          .children("a")
          .append(function () {
            return '<button class="sub-nav-toggler"> <i class="fa fa-angle-down"></i> </button>'
          })

        var subMenuToggler = $(".navbar-nav .sub-nav-toggler")

        subMenuToggler.on("click", function () {
          $(this).parent().parent().children(".sub-menu").slideToggle()
          return false
        })
      }

      $(".sticky-area").sticky({
        topSpacing: 0,
      })
    })
  },
  computed: {
    ...mapState('auth-module', {
      isLoggedIn: state => !!state.currentUser,
      userRole: state => state.currentUser.role.name,
    }),
  },
  mounted() {
    this.$store.dispatch('auth-module/checkUser')
  },
}
</script>
