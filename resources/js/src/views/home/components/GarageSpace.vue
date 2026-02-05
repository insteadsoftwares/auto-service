<template>
  <div class="about-area section-padding gray-bg" id="client-space">
    <div class="container">
		<div class="booking-wrapper">
			<!-- Sidebar -->
			<aside class="sidebar">
				<div class="profile">
					<img src="/homePage/img/user-image.jpg" alt="profile" />
					<h4 class="mt-3">{{ userName }}</h4>
					<p>{{ userEmail }}</p>
				</div>

				<ul class="menu">
					<li
						v-for="item in menu"
						:key="item.key"
						:class="{ active: activeTab === item.key }"
						@click="activeTab = item.key"
					>
						{{ item.label }}
					</li>
				</ul>
			</aside>

			<!-- Content -->
			<main class="content">
				<!-- Dashboard -->
				<div v-if="activeTab === 'dashboard'">
					<Dashboard/>
				</div>
				
				<!-- garage info -->
				<div v-if="activeTab === 'garage'">
					<GarageInfo :garage="garage"/>
				</div>
				
				<!-- garage Services -->
				<div v-if="activeTab === 'garageServices'">
					<GarageServices :garage="garage"/>
				</div>

				<!-- garage Specialties -->
				<div v-if="activeTab === 'garageSpecialties'">
					<GarageSpecialties :garage="garage"/>
				</div>

				<!-- Upcoming Appointment -->
				<div v-if="activeTab === 'appointment'">
					<UpcomingGarageAppointments @garage="garage"/>
				</div>

				<!-- Expired Appointment -->
				<div v-if="activeTab === 'appointmentHistory'">
					<ExpiredGarageAppointments @garage="garage"/>
				</div>
			</main>
		</div>
	</div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import store from '@/store'
import GarageInfo from './garage/GarageInfo.vue'
import GarageServices from './garage/GarageServices.vue'
import GarageSpecialties from './garage/GarageSpecialties.vue'
import UpcomingGarageAppointments from './appointment/UpcomingGarageAppointments.vue'
import ExpiredGarageAppointments from './appointment/ExpiredGarageAppointments.vue'
import Dashboard from './dashboard/Dashboard.vue'

export default {
  name: "ClientSpace",
  components: {
	GarageInfo,
	GarageServices,
	GarageSpecialties,
	UpcomingGarageAppointments,
	ExpiredGarageAppointments,
	Dashboard,
  },
  data() {
    return {
	  activeTab: "dashboard",
      menu: [
        { key: "dashboard", label: "Tableau de bord" },
        { key: "garage", label: "Garage" },
        { key: "garageServices", label: "Nos Services" },
        { key: "garageSpecialties", label: "Nos Spécialités" },
        { key: "appointment", label: "Nos Rendez-vous" },
        { key: "appointmentHistory", label: "Historique RDV" },
      ],
      vehicleToEdit: null,
      garage: null,
    };
  },
  computed: {
    ...mapState('auth-module', {
      userName: state => state.currentUser ? `${state.currentUser.first_name} ${state.currentUser.last_name}` : '',
      userEmail: state => state.currentUser ? `${state.currentUser.email}` : '',
    }),
  },
  methods: {
	//
  },
  created() {
	this.$store.dispatch('garage-module/getGarageByTechnician').then(() => {
		this.garage = store.getters['garage-module/garageByTechnician']
	})
  }
};
</script>

<style>
@import '../../../../../../public/homePage/css/clientSpace.css';
</style>
