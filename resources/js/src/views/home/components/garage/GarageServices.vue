<template>
  <div id="garage-details">
	<b-row style="border-bottom: 1px solid #d4d4d4; padding-bottom: 21px; margin-bottom: 40px;">
		<b-col class="col-lg-6 col-md-6 col-sm-12">
			<h3 style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">Nos Services</h3>
		</b-col>
		<b-col sm="12" md="6" lg="6" class="d-flex justify-content-lg-end justify-content-md-end justify-content-sm-start mt-sm-2 mt-lg-0 mt-md-0">
			<b-button class="bg-style" @click="$bvModal.show('add-services-modal')" style="border: none;">
				Ajouter des services
			</b-button>
		</b-col>
	</b-row>

	<div class="table-wrapper">
		<table>
			<thead>
				<tr>
					<th>Service</th>
					<th>Description</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="garageService in paginatedServices" :key="garageService.id">
					<td>
						<span v-if="garageService.service">{{ garageService.service.name }}</span>
						<span v-else>-</span>
					</td>
					<td>
						<span v-if="garageService.service">{{ garageService.service.description }}</span>
						<span v-else>-</span>
					</td>
					<td>
						<span class="status cursor-pointer cancelled" @click="deleteService(garageService.service_id)">
							Supprimer
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
	<AddService :garageServices="services" @services-added="addNewServices"/>
  </div>
</template>

<script>
import { BFormInput, BFormTextarea, BButton, BCollapse, BRow, BCol, BDropdown, BDropdownItem } from 'bootstrap-vue'
import store from '@/store'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import AddService from './AddService.vue'

export default {
  components: {
    BFormInput,
    BFormTextarea,
    BButton,
    BCollapse,
	BRow, 
	BCol,
    AppTimeline,
    AppTimelineItem,
	BDropdown, 
	BDropdownItem,
	AddService
  },
  props: {
    garage: {
		type: Object,
		default: null,
	},
  },
  data() {
    return {
	  currentPage: 1,
      perPage: 10,
	  services: [],
    }
  },
  watch: {
	garage: {
		immediate: true,
		handler(newVal) {
			if (newVal && newVal.garage_services) {
				this.services = [...newVal.garage_services]
			}
		}
	}
  },
  computed: {
    totalPages() {
      return Math.ceil(this.services.length / this.perPage)
    },
    paginatedServices() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.services.slice(start, end)
    },
  },
  methods: {
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
	deleteService(service_id){
		this.$bvModal.msgBoxConfirm('Êtes-vous sûr de vouloir supprimer ce service?', {
          title: 'Veuillez confirmer',
          centered: true,
          okVariant: 'danger',
          okTitle: 'Confirmer',
          cancelTitle: 'Annuler'
        })
		.then(value => {
            if (value) {
              store.dispatch('garage-service-module/deleteGarageService', service_id).then(() => {
				this.$toast.success('Le service a été supprimée avec succès')
				this.services = this.services.filter(
					s => s.service_id !== service_id
				)
              }).catch(() => {
				this.$toast.error('An unexpected error occured! Please retry')
              })
            }
		})
		.catch(err => {
            console.log(err)
		})
	},
	addNewServices(newServices) {
		this.services = [...this.services, ...newServices]
	}
  },
}
</script>