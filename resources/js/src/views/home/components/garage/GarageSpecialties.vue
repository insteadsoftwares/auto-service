<template>
  <div id="garage-details">
	<b-row style="border-bottom: 1px solid #d4d4d4; padding-bottom: 21px; margin-bottom: 40px;">
		<b-col class="col-lg-6 col-md-6 col-sm-12">
			<h3 style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">Nos Spécialités</h3>
		</b-col>
		<b-col sm="12" md="6" lg="6" class="d-flex justify-content-lg-end justify-content-md-end justify-content-sm-start mt-sm-2 mt-lg-0 mt-md-0">
			<b-button class="bg-style" @click="$bvModal.show('add-specialty-modal')" style="border: none;">
				Ajouter des spécialités
			</b-button>
		</b-col>
	</b-row>
	<div class="table-wrapper">
		<table>
			<thead>
				<tr>
					<th>Type de véhicule</th>
					<th>Marque de véhicule</th>
					<th>Modèle de véhicule</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="garageSpecialty in paginatedSpecialties" :key="garageSpecialty.id">
					<td>
						<span v-if="garageSpecialty.vehicle_type">{{ garageSpecialty.vehicle_type.type }}</span>
						<span v-else>-</span>
					</td>
					<td>
						<span v-if="garageSpecialty.vehicle_brand">{{ garageSpecialty.vehicle_brand.name }}</span>
						<span v-else>-</span>
					</td>
					<td>
						<span v-if="garageSpecialty.vehicle_modele">{{ garageSpecialty.vehicle_modele.modele }}</span>
						<span v-else>-</span>
					</td>
					<td>
						<span class="status cursor-pointer cancelled" @click="deleteSpecialty(garageSpecialty.id)">
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
	<AddSpecialty :garageSpecialties="specialties" @specialties-added="addNewSpecialties"/>
  </div>
</template>

<script>
import { BFormInput, BFormTextarea, BButton, BCollapse, BRow, BCol, BDropdown, BDropdownItem } from 'bootstrap-vue'
import store from '@/store'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import AddSpecialty from './AddSpecialty.vue'

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
	AddSpecialty,
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
	  specialties: [],
    }
  },
  watch: {
	garage: {
		immediate: true,
		handler(newVal) {
			if (newVal && newVal.garage_specialties) {
				this.specialties = [...newVal.garage_specialties]
			}
		}
	}
  },
  computed: {
    totalPages() {
      return Math.ceil(this.specialties.length / this.perPage)
    },
    paginatedSpecialties() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.specialties.slice(start, end)
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
	deleteSpecialty(specialty_id){
		this.$bvModal.msgBoxConfirm('Êtes-vous sûr de vouloir supprimer cette spécialité?', {
          title: 'Veuillez confirmer',
          centered: true,
          okVariant: 'danger',
          okTitle: 'Confirmer',
          cancelTitle: 'Annuler'
        })
		.then(value => {
            if (value) {
              store.dispatch('garage-specialty-module/deleteGarageSpecialty', specialty_id).then(() => {
				this.$toast.success('La spécialité a été supprimée avec succès')
				this.specialties = this.specialties.filter(
					s => s.id !== specialty_id
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
	addNewSpecialties(newSpecialties){
		this.specialties = [...this.specialties, ...newSpecialties]
	}
  },
}
</script>