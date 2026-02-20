<template>
  <section class="hero-wrapper">
	<div class="hero-fixed-form" v-if="(isLoggedIn && userRole == 'client') || !isLoggedIn">
	  <Appointment/>
    </div>

	<div class="homepage-slides owl-carousel">
		<div class="single-slide-item">
			<div class="overlay" />
				<div class="hero-area-content">
					<div class="container">
						<!-- <div class="row justify-content-center"> -->
						<div class="row">
							<div class="col-lg-8 wow fadeInUp animated" data-wow-delay=".2s">
								<div class="section-title">
									<h1>Entretien & <br>Réparation Professionnelle</h1>
									<p>Nos techniciens qualifiés assurent un service rapide et fiable pour maintenir votre véhicule en parfait état.</p>
								</div>
								<!-- <router-link class="main-btn" :to="{ name: 'ServicesPage' }">En savoir plus</router-link> -->
							</div>
						</div>
					</div>
				</div>
			</div>

		<div class="single-slide-item hero-area-bg-2">
		<div class="overlay" />
			<div class="hero-area-content">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 wow fadeInUp animated" data-wow-delay=".2s">
							<div class="section-title">
								<h1>Changement de Pneus & <br>Contrôle Sécurité</h1>
								<p>Nous remplaçons vos pneus et vérifions votre véhicule pour garantir votre sécurité sur la route.</p>
							</div>
							<!-- <router-link class="main-btn" :to="{ name: 'ServicesPage' }">En savoir plus</router-link> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  </section>
</template>

<script>
import { onMounted } from "@vue/composition-api"
import Appointment from "./Appointment.vue"
import { mapState } from 'vuex'

export default {
  name: "HeroCarousel",
  components: {
    Appointment
  },
  setup() {
    onMounted(() => {
      $('.homepage-slides').owlCarousel({
        items: 1,
        dots: false,
        nav: true,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        smartSpeed: 2000,
        slideSpeed: 600,
        animateOut: 'fadeOut',
        navText: ["<i class='la la-angle-left'></i>", "<i class='la la-angle-right'></i>"],
        responsive: {
          0: { items: 1, nav: false, dots: false },
          600: { items: 1, nav: false, dots: false },
          768: { items: 1, nav: false, dots: false },
          1100: { items: 1, nav: true, dots: false },
        },
      })

      $(".homepage-slides").on("translate.owl.carousel", function () {
        $(".single-slide-item h1").removeClass("animated fadeInUp").css("opacity", "1")
        $(".single-slide-item h6").removeClass("animated fadeInDown").css("opacity", "1")
        $(".single-slide-item p").removeClass("animated fadeInDown").css("opacity", "1")
        $(".single-slide-item a.main-btn").removeClass("animated fadeInDown").css("opacity", "1")
      })

      $(".homepage-slides").on("translated.owl.carousel", function () {
        $(".single-slide-item h1").addClass("animated fadeInUp").css("opacity", "1")
        $(".single-slide-item h6").addClass("animated fadeInDown").css("opacity", "1")
        $(".single-slide-item p").addClass("animated fadeInDown").css("opacity", "1")
        $(".single-slide-item a.main-btn").addClass("animated fadeInDown").css("opacity", "1")
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
<style >
.hero-wrapper {
  position: relative;
}

/* Formulaire fixe */
.hero-fixed-form {
  position: absolute;
  top: 50%;
  right: 80px;
  transform: translateY(-50%);
  width: 370px;
  background: #f35c279c;
  padding: 25px;
  z-index: 998;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  color: white;
}

/* Inputs */
/* .hero-fixed-form input,
.hero-fixed-form select {
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border: 1px solid #ddd;
}

.hero-fixed-form button {
  width: 100%;
  background: #fff;
  color: #ff5a1f;
  border: none;
  padding: 12px;
  font-weight: bold;
  cursor: pointer;
} */

@media (max-width: 768px) {
  .hero-fixed-form {
    position: static;
    transform: none;
    width: 100%;
    /* margin: 30px auto; */
  	background: #F35C27;
  }
}

</style>