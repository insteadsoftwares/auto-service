<template>
  <div>
	<h3>Tableau de bord</h3>
	<b-row>
		<b-col cols="12">
			<Statistics/>
      	</b-col>
	</b-row>
	<b-row>
		<b-col cols="12">
			<AppointmentsEvolution/>
      	</b-col>
	</b-row>
	<b-row>
		<b-col cols="12">
			<AppointmentsByService/>
      	</b-col>
	</b-row>
	<b-row>
		<b-col cols="12">
			<ThreeBestClients/>
      	</b-col>
	</b-row>
  </div>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import Statistics from './Statistics.vue'
import AppointmentsEvolution from './AppointmentsEvolution.vue'
import AppointmentsByService from './AppointmentsByService.vue'
import ThreeBestClients from './ThreeBestClients.vue'

export default {
  name: "Dashboard",
  components: {
	BRow,
	BCol,
	Statistics,
	AppointmentsEvolution,
	AppointmentsByService,
	ThreeBestClients,
  },
};
</script>