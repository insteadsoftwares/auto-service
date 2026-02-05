<template>
  <footer class="footer-area">
    <div class="container">
      <div class="footer-up">
        <div class="row gy-5">
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="logo">
              <img
                src="/homePage/img/logo-white.png"
                alt="dustrian-logo"
              >
            </div>
            <p>We are the leaders in the building <br> construction and factories. We’re <br> around over the worldwide. </p>
            <div class="social-area">
              <a href=""><i class="lab la-facebook-f" /></a>
              <a href=""><i class="lab la-instagram" /></a>
              <a href=""><i class="lab la-twitter" /></a>
              <a href=""><i class="la la-skype" /></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 com-sm-12">
            <h5>Explore</h5>
            <ul>
              <li>
				<RouterLink :to="{name:'HomePage'}">
                  Accueil
				</RouterLink>
				<RouterLink :to="{name:'ServicesPage'}">
                  Services
				</RouterLink>
				<RouterLink :to="{name:'AboutPage'}">
                  À propos
				</RouterLink> 
				<RouterLink :to="{name:'ContactPage'}">
                  Contact
				</RouterLink> 
              </li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12">
            <h5>Nos Services</h5>
            <ul>
              <li >
				<div v-for="service in services" :key="service.id">
					<a href="" @click.prevent="goToService(service.id)">
						<span>-</span> {{ service.title }}
					</a>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12">
            <h5>Contact</h5>
            <div class="contact-content">
				<div class="contact-inner">
					<div>
						<div>
							<i class="las la-map-marker"></i>
							<span>Adresse</span>
						</div>
						<p>66 Broklyn St. NY, USA</p>
					</div>
				</div>
				<div class="contact-inner">
					<div>
						<div>
							<i class="las la-envelope"></i>
							<span>E-mail</span>
						</div>
						<p>auto.service@gmail.com</p>
					</div>
				</div>
				<div class="contact-inner">
					<div>
						<div>
							<i class="las la-phone"></i>
							<span>Téléphone</span>
						</div>
						<p>+216 58 476 718</p>
					</div>
				</div>
			</div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from "axios";

export default {
  name: "FooterArea",
  data() {
    return {
      services: []
    };
  },
  mounted() {
    // Si tu veux récupérer depuis un JSON externe
    // axios.get("/data/services.json").then(res => {
    //   this.services = res.data;
    // });

    this.services = [
      {
        id: "1",
        title: "Entretien et Réparation",
      },
      {
        id: "2",
        title: "Changement d’Huile",
      },
      {
        id: "3",
        title: "Batterie et Électricité",
      },
      {
        id: "4",
        title: "Pneus et Alignement",
      },
      {
        id: "5",
        title: "Lavage & Esthétique Auto",
      },
      {
        id: "6",
        title: "Diagnostic Moteur",
      }
    ];
  },
  methods: {
	goToService(id) {
		localStorage.setItem("selectedServiceId", id);
		this.$router.push({ name: "ServicesPage" });
	}
  }
};
</script>