
<template>
	<div>
		<h3>Mes Rendez-vous</h3>
		<div class="table-wrapper">
			<table>
				<thead>
					<tr>
						<th>Client</th>
						<th>Service</th>
						<th>Date/Heure</th>
						<th>Type</th>
						<th>Marque</th>
						<th>Modèle</th>
						<th>État</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="paginatedAppointments.length === 0">
						<td colspan="7" class="empty-row">
							Aucun rendez-vous trouvé.
						</td>
					</tr>
					<tr v-else v-for="expiredAppointment in paginatedAppointments" :key="expiredAppointment.id">
						<td>{{ expiredAppointment.client.first_name }} {{ expiredAppointment.client.last_name }}</td>
						<td>
							<span v-if="expiredAppointment.service">{{ expiredAppointment.service.name }}</span>
							<span v-else>-</span>
						</td>
						<td>
							{{ formatDate(expiredAppointment.appointment_date) }}<br>
							{{ formatTime(expiredAppointment.appointment_time) }}
						</td>
						<td v-if="expiredAppointment.vehicle">
							<span v-if="expiredAppointment.vehicle.vehicle_type">{{ expiredAppointment.vehicle.vehicle_type.type }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td v-if="expiredAppointment.vehicle">
							<span v-if="expiredAppointment.vehicle.vehicle_brand">{{ expiredAppointment.vehicle.vehicle_brand.name }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td v-if="expiredAppointment.vehicle">
							<span v-if="expiredAppointment.vehicle.vehicle_modele">{{ expiredAppointment.vehicle.vehicle_modele.modele }}</span>
							<span v-else>-</span>
						</td>
						<td v-else>-</td>
						<td>
							<span v-if="expiredAppointment.status != 'completed' && expiredAppointment.status != 'cancelled'">
								<span :class="['status cursor-pointer', expiredAppointment.status]" @click="openStatusMenu(expiredAppointment)">
									{{ formatStatus(expiredAppointment.status) }}
								</span>
								<div v-if="activeAppointment === expiredAppointment.id" class="status-menu">
									<!-- <div @click="updateAppointmentStatus('pending', expiredAppointment.id)" class="status-item"> En attente </div>
									<div @click="updateAppointmentStatus('confirmed', expiredAppointment.id)" class="status-item"> Confirmé </div>
									<div @click="updateAppointmentStatus('cancelled', expiredAppointment.id)" class="status-item"> Annulé </div> -->
									<div @click="updateAppointmentStatus('completed', expiredAppointment.id)" class="status-item"> Terminé </div>
								</div>
							</span>
							<span v-else>
								<span :class="['status', expiredAppointment.status]">
									{{ formatStatus(expiredAppointment.status) }}
								</span>
							</span>
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
      expiredAppointments: [],
	  activeAppointment: null,
	  currentPage: 1,
      perPage: 10,
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.expiredAppointments.length / this.perPage)
    },
    paginatedAppointments() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.expiredAppointments.slice(start, end)
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
			const appointment = this.expiredAppointments.find(a => a.id === appointment_id)
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
	this.$store.dispatch('appointment-module/getExpiredTechnicianAppointments').then(() => {
		this.expiredAppointments = store.getters['appointment-module/expiredTechnicianAppointments']
	})
  }
}
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>