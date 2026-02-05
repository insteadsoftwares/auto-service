
<template>
	<div class="about-area section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-12 col-sm-12">
					<div class="info-content-area">
						<h2>Liste des Rendez-vous</h2>

						<div style="margin-top: 30px;" v-if="upcomingAppointments.length > 0">
							<div class="toggle-header d-flex align-items-center justify-content-between" @click="upcomingAppointmentsIsOpen = !upcomingAppointmentsIsOpen" >
								<h6 class="mb-0"><strong>Nouveaux Rendez-vous</strong></h6>
								<i class="las" :class="upcomingAppointmentsIsOpen ? 'la-angle-up' : 'la-angle-down'"></i>
							</div>
							<b-collapse v-model="upcomingAppointmentsIsOpen">
								<b-row v-for="upcomingAppointment in upcomingAppointments" :key="upcomingAppointment.id" class="appointment-item mx-0">
									<div class="icon-edit-position" v-if="upcomingAppointment.status != 'cancelled'">
										<i class="la la-calendar-times" @click="cancelAppointment(upcomingAppointment.id)" title="Annuler le rendez-vous"></i>
									</div>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Garage:</strong> {{ upcomingAppointment.garage.name }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Service:</strong> {{ upcomingAppointment.service.name }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Date:</strong> {{ formatDate(upcomingAppointment.appointment_date) }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Heure:</strong> {{ formatTime(upcomingAppointment.appointment_time) }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Type:</strong> {{ upcomingAppointment.vehicle.vehicle_type.type }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Matricule:</strong>
										<span v-if="upcomingAppointment.vehicle.registration_number">{{ upcomingAppointment.vehicle.registration_number }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Année:</strong>
										<span v-if="upcomingAppointment.vehicle.year">{{ upcomingAppointment.vehicle.year }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Marque:</strong>
										<span v-if="upcomingAppointment.vehicle.vehicle_brand">{{ upcomingAppointment.vehicle.vehicle_brand.name }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Modèle:</strong>
										<span v-if="upcomingAppointment.vehicle.vehicle_modele">{{ upcomingAppointment.vehicle.vehicle_modele.modele }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>État:</strong> 
										{{ formatStatus(upcomingAppointment.status) }}		
									</b-col>
									<b-col cols="12" class="mb-2">
										<strong>description:</strong>
										<span v-if="upcomingAppointment.vehicle.description">{{ vehicle.description }}</span>
										<span v-else>-</span>
									</b-col>
								</b-row>
							</b-collapse>
						</div>

						<div style="margin-top: 30px;" v-if="expiredAppointments.length > 0">
							<div class="toggle-header d-flex align-items-center justify-content-between" @click="expiredAppointmentsIsOpen = !expiredAppointmentsIsOpen" >
								<h6 class="mb-0"><strong>Nouveaux Rendez-vous</strong></h6>
								<i class="las" :class="expiredAppointmentsIsOpen ? 'la-angle-up' : 'la-angle-down'"></i>
							</div>
							<b-collapse v-model="expiredAppointmentsIsOpen">
								<b-row v-for="upcomingAppointment in expiredAppointments" :key="upcomingAppointment.id" class="appointment-item mx-0">
									<b-col cols="12" md="6" class="mb-2">
										<strong>Garage:</strong> {{ upcomingAppointment.garage.name }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Service:</strong> {{ upcomingAppointment.service.name }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Date:</strong> {{ formatDate(upcomingAppointment.appointment_date) }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Heure:</strong> {{ formatTime(upcomingAppointment.appointment_time) }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Type:</strong> {{ upcomingAppointment.vehicle.vehicle_type.type }}
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Matricule:</strong>
										<span v-if="upcomingAppointment.vehicle.registration_number">{{ upcomingAppointment.vehicle.registration_number }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Année:</strong>
										<span v-if="upcomingAppointment.vehicle.year">{{ upcomingAppointment.vehicle.year }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Marque:</strong>
										<span v-if="upcomingAppointment.vehicle.vehicle_brand">{{ upcomingAppointment.vehicle.vehicle_brand.name }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>Modèle:</strong>
										<span v-if="upcomingAppointment.vehicle.vehicle_modele">{{ upcomingAppointment.vehicle.vehicle_modele.modele }}</span>
										<span v-else>-</span>
									</b-col>
									<b-col cols="12" md="6" class="mb-2">
										<strong>État:</strong> 
										{{ formatStatus(upcomingAppointment.status) }}		
									</b-col>
									<b-col cols="12" class="mb-2">
										<strong>description:</strong>
										<span v-if="upcomingAppointment.vehicle.description">{{ vehicle.description }}</span>
										<span v-else>-</span>
									</b-col>
								</b-row>
							</b-collapse>
						</div>
					</div>
				</div>

				<div class="col-lg-4">
					<QuestionSection/>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { BRow, BCol, BDropdown, BDropdownItem, BCollapse } from 'bootstrap-vue'
import store from '@/store'
import QuestionSection from '../contact/QuestionSection.vue'

export default {
  components: {
    BRow,
    BCol,
	BDropdown,
	BDropdownItem,
	BCollapse,
	QuestionSection
  },
  data() {
    return {
      upcomingAppointments: [],
	  expiredAppointments: [],
	  upcomingAppointmentsIsOpen: true,
	  expiredAppointmentsIsOpen: false
    }
  },
  methods: {
	formatDate(date) {
		if (!date) return '-'
		return new Intl.DateTimeFormat('fr-FR').format(new Date(date))
	},
	formatTime(time) {
		if (!time) return '-'
		return time.slice(0, 5)
	},
	formatStatus(status) {
		const statuses = {
			pending: 'En attente',
			confirmed: 'Confirmé',
			cancelled: 'Annulé',
			completed: 'Terminé',
		}
		return statuses[status] || status
	},
	cancelAppointment(id){
		this.$store.dispatch('appointment-module/cancelAppointment', id)
		.then(() => {
			this.$toast.success('Rendez-vous annuler')
			this.upcomingClientAppointments()
		})
		.catch(err => {
			console.error(err)
			this.$toast.error('Erreur lors de la réservation')
		})
	},
	upcomingClientAppointments(){
		this.$store.dispatch('appointment-module/getUpcomingClientAppointments').then(() => {
			this.upcomingAppointments = store.getters['appointment-module/upcomingClientAppointments']
		})
	}
  },
  created() {
	this.upcomingClientAppointments()
    store.dispatch('appointment-module/getExpiredClientAppointments').then(() => {
		this.expiredAppointments = store.getters['appointment-module/expiredClientAppointments']
	})
  },
}
</script>
<style scoped>
.icon-edit-position{
	position: absolute;
	right: -5px;
  	top: 5px;
	width: auto;
}

.icon-edit-position i {
  cursor: pointer;
}
</style>