<template>
  <div class="mt-4" id="dashboard-garage">
	<div class="dashboard-frame">
		<div class="mb-4">
			<h4>Nombre de rendez-vous par service</h4>
		</div>
		<b-row>
			<b-col md="4" sm="12" class="mb-2">
				<v-select
					:options="statusOptions"
					v-model="status"
					placeholder="Statut"
					:clearable="false"
					label="label"
					:reduce="option => option.value"
					@input="onFilterChange"
				/>
			</b-col>
			<b-col md="4" sm="12" class="mb-2">
				<flat-pickr
					v-model="startDate"
					:config="dateConfig"
					placeholder="Date début"
					@input="onStartDateChange"
				/>
			</b-col>
			<b-col md="4" sm="12" class="mb-2">
				<flat-pickr
					v-model="endDate"
					:config="dateConfig"
					placeholder="Date fin"
					@input="onEndDateChange"
				/>
			</b-col>
		</b-row>
		<AppointmentsEvolutionChart :chartData="appointmentsEvolution"/>
	</div>
  </div>
</template>

<script>
import store from '@/store'
import { BRow, BCol } from 'bootstrap-vue'
import vSelect from 'vue-select'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import AppointmentsEvolutionChart from './charts/AppointmentsEvolutionChart.vue'

export default {
  name: "AppointmentsEvolution",
  components: {
	BRow,
	BCol,
	FlatPickr,
	vSelect,
	AppointmentsEvolutionChart,
  },
  data() {
    return {
	  appointmentsEvolution: [],
	  startDate: null,
	  endDate: null,
	  status: 'all',
	  statusOptions: [{value: 'all', label : 'Tout'}, {value: 'pending', label : 'En attente'}, {value: 'confirmed', label : 'Confirmé'},
		{value: 'cancelled', label : 'Annulé'}, {value: 'completed', label : 'Terminé'}],
	  dateConfig: {
        dateFormat: 'Y-m-d',
		altInput: true, 
		altFormat: 'd/m/Y',
      },
    };
  },
  methods: {
	onStartDateChange(newStart) {
		if (this.endDate && new Date(this.endDate) < new Date(newStart)) {
			const newEnd = new Date(newStart)
			newEnd.setDate(newEnd.getDate() + 1)
			this.endDate = newEnd
		}
		this.fetchData()
	},
	onEndDateChange(newEnd) {
		if (this.startDate && new Date(newEnd) < new Date(this.startDate)) {
			const newStart = new Date(newEnd)
			newStart.setDate(newStart.getDate() - 1)
			this.startDate = newStart
		}
		this.fetchData()
	},
	onFilterChange() {
		this.fetchData()
	},
	fetchData() {
		console.log('fetchData')
		if(this.endDate != null && this.startDate != null){
			store.dispatch('statistic-module/getAppointmentsEvolutionByDate', { start_date: this.startDate, end_date: this.endDate, status: this.status }).then(() => {
				this.appointmentsEvolution = store.getters['statistic-module/appointmentsEvolution']
			})
		}
	}
  },
  created() {
	const today = new Date()
	const todayStr = today.toISOString().slice(0, 10)

	const oneMonthAgo = new Date(today)
	oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1)
	const oneMonthAgoStr = oneMonthAgo.toISOString().slice(0, 10)

	this.endDate = todayStr
	this.startDate = oneMonthAgoStr
	this.fetchData()
  }
};
</script>