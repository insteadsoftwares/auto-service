<template>
  <div class="services-area gray-bg section-padding pad-bot-90">
    <div class="container">
      <div class="row">
        <div class="offset-lg-2 col-lg-8 text-center">
          <div class="section-title">
            <h2>Nos Services</h2>
          </div>
        </div>
      </div>

      <div class="row mt-30">
        <div class="col-lg-12">
          <div class="service-slider owl-carousel">
            <div
              v-for="service in services.slice(0,6)"
              :key="service.id"
              class="single-service-area p-0"
            >
              <div class="service-icon">
                <img
                  :src="'/homePage/img/service/' + service.image"
                  alt=""
                  style="height: 180px;"
                >
              </div>
              <div class="p-3">
                <h5>{{ service.name }}</h5>
                <p class="truncate-text">
                  {{ service.description }}
                </p>
				<a href="" class="read-more" @click.prevent="goToService(service.id)">
					En savoir plus
				</a>
              </div>
            </div> 						
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import store from '@/store'

export default {
  name: "ServicesSlider",
  data() {
    return {
      services: []
    };
  },
  methods: {
	goToService(id) {
		localStorage.setItem("selectedServiceId", id);
		this.$router.push({name: 'ServicesPage', query: { service_id: id } });
	},
	initCarousel() {
		const $carousel = $(".service-slider")

		if ($carousel.hasClass("owl-loaded")) {
			$carousel.trigger("destroy.owl.carousel")
			$carousel.removeClass("owl-loaded")
			$carousel.find(".owl-stage-outer").children().unwrap()
		}

		$carousel.owlCarousel({
			loop: true,
			items: 4,
			dots: true,
			nav: false,
			margin: 30,
			autoplay: true,
			responsive: {
				0: { items: 1 },
				600: { items: 2 },
				768: { items: 3 },
				1100: { items: 4 }
			}
		})
	}
  },
  created() {
	store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes

		this.$nextTick(() => {
			this.initCarousel()
		})
	})
  }
};
</script>