<template>
  <div class="mt-4" id="dashboard-garage">
	<div class="dashboard-frame">
		<div class="mb-4">
			<h4>Évolution du nombre de RDV</h4>
		</div>
		<b-row>
			<b-col md="6" sm="12" class="mb-2">
				<flat-pickr
					v-model="startDate"
					:config="dateConfig"
					placeholder="Date début"
					@input="onStartDateChange"
				/>
			</b-col>
			<b-col md="6" sm="12" class="mb-2">
				<flat-pickr
					v-model="endDate"
					:config="dateConfig"
					placeholder="Date fin"
					@input="onEndDateChange"
				/>
			</b-col>
		</b-row>
		<AppointmentsByServiceChart :chartData="appointmentsInfo"/>
	</div>
  </div>
</template>

<script>
import store from '@/store'
import { BRow, BCol } from 'bootstrap-vue'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import AppointmentsByServiceChart from './charts/AppointmentsByServiceChart.vue'

export default {
  name: "AppointmentsByService",
  components: {
	BRow,
	BCol,
	FlatPickr,
	AppointmentsByServiceChart,
  },
  data() {
    return {
	  appointmentsInfo: null,
	  startDate: null,
	  endDate: null,
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
	fetchData() {
		if(this.endDate != null && this.startDate != null){
			store.dispatch('statistic-module/getAppointmentsCountByService', { start_date: this.startDate, end_date: this.endDate }).then(() => {
				const appointmentsCountByService = store.getters['statistic-module/appointmentsCountByService']
				this.appointmentsInfo = {
					categories: appointmentsCountByService.map(line => line.service?.name || 'Service supprimé'),
					data: appointmentsCountByService.map(line => line.total_rdv)
				};
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