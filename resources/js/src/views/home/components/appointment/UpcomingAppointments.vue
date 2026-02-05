
<template>
	<div>
		<h3>Mes Rendez-vous</h3>
		<div class="table-wrapper">
			<table>
				<thead>
					<tr>
						<th>Garage</th>
						<th>Service</th>
						<th>Date/Heure</th>
						<th>Type</th>
						<th>Marque</th>
						<th>Modèle</th>
						<th>État</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="upcomingAppointments.length === 0">
						<td colspan="7" class="empty-row">
							Aucun rendez-vous trouvé.
						</td>
					</tr>
					<tr v-else v-for="upcomingAppointment in upcomingAppointments" :key="upcomingAppointment.id">
						<td>
							<span v-if="upcomingAppointment.garage">{{ upcomingAppointment.garage.name }}</span>
							<span v-else>-</span>
						</td>
						<td>
							<span v-if="upcomingAppointment.service">{{ upcomingAppointment.service.name }}</span>
							<span v-else>-</span>
						</td>
						<td>
							{{ formatDate(upcomingAppointment.appointment_date) }}<br>
							{{ formatTime(upcomingAppointment.appointment_time) }}
						</td>
						<td v-if="upcomingAppointment.vehicle">
							<span v-if="upcomingAppointment.vehicle.vehicle_type">{{ upcomingAppointment.vehicle.vehicle_type.type }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td v-if="upcomingAppointment.vehicle">
							<span v-if="upcomingAppointment.vehicle.vehicle_brand">{{ upcomingAppointment.vehicle.vehicle_brand.name }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td v-if="upcomingAppointment.vehicle">
							<span v-if="upcomingAppointment.vehicle.vehicle_modele">{{ upcomingAppointment.vehicle.vehicle_modele.modele }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td>
							<span v-if="upcomingAppointment.status != 'cancelled'">
								<span :class="['status cursor-pointer', upcomingAppointment.status]" @click="openStatusMenu(upcomingAppointment)">
									{{ formatStatus(upcomingAppointment.status) }}
								</span>
								<div v-if="activeAppointment === upcomingAppointment.id" class="status-menu">
									<div @click="cancelAppointment(upcomingAppointment.id)" class="status-item"> Annulé </div>
								</div>
							</span>
							<span v-else>
								<span :class="['status', upcomingAppointment.status]">
									{{ formatStatus(upcomingAppointment.status) }}
								</span>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>							
</template>

<script>
import { BRow, BCol, BDropdown, BDropdownItem, BCollapse } from 'bootstrap-vue'
import store from '@/store'

export default {
  components: {
    BRow,
    BCol,
	BDropdown,
	BDropdownItem,
	BCollapse,
  },
  data() {
    return {
      upcomingAppointments: [],
	  activeAppointment: null,
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
			this.activeAppointment = null
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
	},
	openStatusMenu(appointment) {
		this.activeAppointment = this.activeAppointment === appointment.id ? null : appointment.id
	},
  },
  created() {
	this.upcomingClientAppointments()
  },
}
</script>