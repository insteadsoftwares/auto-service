
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Services'"/>
		<div class="service-details-section section-padding">
			<div class="container">
				<div class="row">
					<div class="col-lg-4">
						<div class="service-list">
							<h5>Liste Des Services</h5>
							<div class="services-list">
								<a v-for="(service, index) in services"
									:key="index"
									:class="{ active: activeServiceIndex === index }"
									href="#"
									@click.prevent="selectService(index)">
									{{ service.name }}
									<span><i class="las la-arrow-right"></i></span>
								</a>
							</div>
						</div>

						<div class="helpline-section">
							<div class="helpline-content text-center">
								<h4>Besoin d’un rendez-vous ?</h4>
								<p>Notre équipe est prête à vous accompagner</p>
								<button class="btn btn-primary" @click="goToAppointment()" type="submit">Prendre rendez-vous</button>
							</div>
						</div>
					</div>

					<div class="col-lg-8" v-if="services.length">
						<div class="single-service mt-0">
							<img :src="'/homePage/img/service/' + services[activeServiceIndex].image" alt="">
							<h2>{{ services[activeServiceIndex].name }}</h2>
							<p>{{ services[activeServiceIndex].description }}</p>
							<hr>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import store from '@/store'

export default {
  name: "ServicesPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
  },
  data() {
    return {
      activeServiceIndex: 0,
	  services: [],
    }
  },
  mounted() {
	const savedId = localStorage.getItem("selectedServiceId");

	if (savedId) {
		const index = this.services.findIndex(s => s.id == savedId);
		if (index !== -1) {
			this.activeServiceIndex = index;
		}
		localStorage.removeItem("selectedServiceId");
	} else {
		this.activeServiceIndex = 0;
	}
  },
  methods: {
    selectService(index) {
      this.activeServiceIndex = index;
    },
	goToAppointment(){
		const service = this.services[this.activeServiceIndex];
		if (!service) return;
		this.$router.push({path: '/clientSpace', query: { tab: 'addAppointment', service_id: service.id }});
	}
  },
  created() {
	store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes

		const serviceIdFromRoute = this.$route.query.service_id;
		if (serviceIdFromRoute) {
			const index = this.services.findIndex(
				s => String(s.id) === String(serviceIdFromRoute)
			);

			if (index !== -1) {
				this.activeServiceIndex = index;
			}
		}
	})
  }
}
</script>
<style scoped>
.services-list{
	height: 380px;
    overflow-y: scroll;
    padding-right: 5px;
}

/* width */
.services-list::-webkit-scrollbar {
  width: 10px;
}

/* Track */
.services-list::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
.services-list::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
.services-list::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
</style>