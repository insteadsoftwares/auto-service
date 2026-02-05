
<template>
	<div>
		<h3>Historique des rendez-vous</h3>
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
					<tr v-if="paginatedAppointments.length === 0">
						<td colspan="7" class="empty-row">
							Aucun rendez-vous trouvé.
						</td>
					</tr>
					<tr v-else v-for="expiredAppointment in paginatedAppointments" :key="expiredAppointment.id">
						<td>
							<span v-if="expiredAppointment.garage">{{ expiredAppointment.garage.name }}</span>
							<span v-else>-</span>
						</td>
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
							<span :class="['status', expiredAppointment.status]">
								{{ formatStatus(expiredAppointment.status) }}
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
	  expiredAppointments: [],
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
    store.dispatch('appointment-module/getExpiredClientAppointments').then(() => {
		this.expiredAppointments = store.getters['appointment-module/expiredClientAppointments']
	})
  },
}
</script>