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
				<!-- Vehicles -->
				<div v-if="activeTab === 'vehicles'">
					<Vehicles ref="vehiclesComponent" @edit-vehicle="handleEditVehicle"/>
				</div>
				
				<!-- Add Vehicle -->
				<div v-if="activeTab === 'addVehicle'">
					<AddVehicle :vehicle-to-edit="vehicleToEdit" @vehicle-added="handleVehicleAdded"/>
				</div>

				<!-- Upcoming Appointments -->
				<div v-if="activeTab === 'appointments'">
					<UpcomingAppointments/>
				</div>

				<!-- Add Appointment -->
				<div v-if="activeTab === 'addAppointment'">
					<AddAppointment @appointment-added="handleAppointmentAdded"/>
				</div>

				<!-- LISTING -->
				<div v-if="activeTab === 'appointmentHistory'">
					<ExpiredAppointments/>
				</div>
			</main>
		</div>
	</div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import store from '@/store'
import Vehicles from './vehicle/Vehicle.vue'
import AddVehicle from './vehicle/AddVehicle.vue'
import UpcomingAppointments from './appointment/UpcomingAppointments.vue'
import AddAppointment from './appointment/AddAppointment.vue'
import ExpiredAppointments from './appointment/ExpiredAppointments.vue'

export default {
  name: "ClientSpace",
  components: {
	Vehicles,
	AddVehicle,
	UpcomingAppointments,
	AddAppointment,
	ExpiredAppointments,
  },
  data() {
    return {
	  activeTab: "vehicles",
      menu: [
        { key: "vehicles", label: "Véhicules" },
        { key: "addVehicle", label: "Ajouter véhicule" },
        { key: "appointments", label: "Mes Rendez-vous" },
        { key: "addAppointment", label: "Prendre Rendez-vous" },
        { key: "appointmentHistory", label: "Historique RDV" },
      ],
      vehicleToEdit: null,
    };
  },
  computed: {
    ...mapState('auth-module', {
      userName: state => state.currentUser ? `${state.currentUser.first_name} ${state.currentUser.last_name}` : '',
      userEmail: state => state.currentUser ? `${state.currentUser.email}` : '',
    }),
  },
  methods: {
	handleAppointmentAdded() {
		this.activeTab = 'appointments';
	},
	handleVehicleAdded() {
		this.activeTab = 'vehicles';
		// if (this.$refs.vehiclesComponent) {
		// 	this.$refs.vehiclesComponent.refreshVehicles();
		// }
		this.vehicleToEdit = null;
	},
	editVehicle(vehicle) {
		this.$emit('edit-vehicle', vehicle);
	},
	handleEditVehicle(vehicle) {
		this.vehicleToEdit = vehicle;
		this.activeTab = 'addVehicle';
	}
  },
  created() {
	const { tab } = this.$route.query;
	if (tab) {
		this.activeTab = tab;
	}
  }
};
</script>

<style>
@import '../../../../../../public/homePage/css/clientSpace.css';
</style>
