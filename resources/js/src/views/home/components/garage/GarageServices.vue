<template>
  <div id="garage-details">
	<h3>Nos Services</h3>
	<div class="table-wrapper">
		<table>
			<thead>
				<tr>
					<th>Service</th>
					<th>Description</th>
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
import { BFormInput, BFormTextarea, BButton, BCollapse, BRow, BCol, BDropdown, BDropdownItem } from 'bootstrap-vue'
import store from '@/store'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import QuestionSection from '../contact/QuestionSection.vue'

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
	QuestionSection
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
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.garage.garage_services.length / this.perPage)
    },
    paginatedServices() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.garage.garage_services.slice(start, end)
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
  },
}
</script>