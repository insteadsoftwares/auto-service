
<template>
	<div>
		<h3>Mes Rendez-vous</h3>
		<div class="table-wrapper">
			<table>
				<thead>
					<tr>
						<th>Client</th>
						<th>N.téléphone</th>
						<th>Service</th>
						<th>Date/Heure</th>
						<th>Véhicule</th>
						<th>État</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="paginatedAppointments.length === 0">
						<td colspan="7" class="empty-row">
							Aucun rendez-vous trouvé.
						</td>
					</tr>
					<tr v-else v-for="upcomingAppointment in paginatedAppointments" :key="upcomingAppointment.id">
						<td>
							<span v-if="upcomingAppointment.is_client">{{ upcomingAppointment.client.first_name }} {{ upcomingAppointment.client.last_name }}</span>
							<span v-else>{{ upcomingAppointment.guest_name }}</span>
						</td>
						<td>
							<span v-if="upcomingAppointment.is_client">{{ upcomingAppointment.client.phone_number }}</span>
							<span v-else>{{ upcomingAppointment.guest_phone }}</span>
						</td>
						<td>
							<span v-if="upcomingAppointment.service">{{ upcomingAppointment.service.name }}</span>
							<span v-else>-</span>
						</td>
						<td>
							{{ formatDate(upcomingAppointment.appointment_date) }}<br>
							{{ formatTime(upcomingAppointment.appointment_time) }}
						</td>
						<td>
							<span v-if="upcomingAppointment.is_client">
								<span v-if="upcomingAppointment.vehicle">
									<span v-if="upcomingAppointment.vehicle.vehicle_type">{{ upcomingAppointment.vehicle.vehicle_type.type }}</span>
									<span v-else>-</span>
									|
									<span v-if="upcomingAppointment.vehicle.vehicle_brand">{{ upcomingAppointment.vehicle.vehicle_brand.name }}</span>
									<span v-else>-</span>
									|
									<span v-if="upcomingAppointment.vehicle.vehicle_modele">{{ upcomingAppointment.vehicle.vehicle_modele.modele }}</span>
									<span v-else>-</span>
								</span>
								<span v-else>
									-|-|-
								</span>
							</span>
							<span v-else>
								<span v-if="upcomingAppointment.guest_vehicle_type">
									{{ upcomingAppointment.guest_vehicle_type.type }}
								</span>
								<span v-else>-</span>
								|
								<span v-if="upcomingAppointment.guest_vehicle_brand">
									{{ upcomingAppointment.guest_vehicle_brand.name }}
								</span>
								<span v-else>-</span>
								|
								<span v-if="upcomingAppointment.guest_vehicle_modele">
									{{ upcomingAppointment.guest_vehicle_modele.modele }}
								</span>
								<span v-else>-</span>
							</span>
						</td>
						<td>
							<span :class="['status cursor-pointer', upcomingAppointment.status]" @click="openStatusMenu(upcomingAppointment)">
								{{ formatStatus(upcomingAppointment.status) }}
							</span>
							<div v-if="activeAppointment === upcomingAppointment.id" class="status-menu">
								<div @click="updateAppointmentStatus('pending', upcomingAppointment.id)" class="status-item"> En attente </div>
								<div @click="updateAppointmentStatus('confirmed', upcomingAppointment.id)" class="status-item"> Confirmé </div>
								<div @click="updateAppointmentStatus('cancelled', upcomingAppointment.id)" class="status-item"> Annulé </div>
								<!-- <div @click="updateAppointmentStatus('completed', upcomingAppointment.id)" class="status-item"> Terminé </div> -->
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div v-if="totalPages > 1" class="pagination">
				<button
					class="page-btn arrow"
					:disabled="currentPage === 1"
					@click="prevPage"
				>
					‹
				</button>

				<!-- Bouton pages -->
				<button
					v-for="page in totalPages"
					:key="page"
					class="page-btn"
					:class="{ active: page === currentPage }"
					@click="currentPage = page"
				>
					{{ page }}
				</button>

				<!-- Bouton suivant -->
				<button
					class="page-btn arrow"
					:disabled="currentPage === totalPages"
					@click="nextPage"
				>
					›
				</button>
			</div>
		</div>
	</div>							
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import store from '@/store'
import vSelect from 'vue-select'

export default {
  components: {
    BRow,
    BCol,
    vSelect,
  },
  data() {
    return {
      upcomingAppointments: [],
	  activeAppointment: null,
	  currentPage: 1,
      perPage: 10,
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.upcomingAppointments.length / this.perPage)
    },
    paginatedAppointments() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.upcomingAppointments.slice(start, end)
    },
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
	openStatusMenu(appointment) {
		this.activeAppointment = this.activeAppointment === appointment.id ? null : appointment.id
	},
	async updateAppointmentStatus(status, appointment_id){
		try {
			const updatedAppointment = await this.$store.dispatch('appointment-module/editAppointmentStatus', {'status': status, 'appointment_id': appointment_id})
			const appointment = this.upcomingAppointments.find(a => a.id === appointment_id)
			if (appointment) appointment.status = store.getters['appointment-module/appointmentStatus'].status
			this.activeAppointment = null
			this.$toast.success('Le statut a été modifié avec succès')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
	},
	nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--
      }
    },
  },
  created() {
	this.$store.dispatch('appointment-module/getUpcomingTechnicianAppointments').then(() => {
		this.upcomingAppointments = store.getters['appointment-module/upcomingTechnicianAppointments']
	})
  }
}
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>