<template>
  <div>
	<b-row class="match-height">
		<b-col md="12" lg="12">
			<Statistics/>
		</b-col>
	</b-row>
	<b-row class="match-height">
		<b-col md="12" lg="4" sm="12">
			<BestServices/>
		</b-col>
		<b-col md="12" lg="8">
            <BestGarages/>
		</b-col>
	</b-row>
	<b-row class="match-height">
		<b-col md="12" lg="4" sm="12">
			<Appointment/>
		</b-col>
		<b-col md="12" lg="4" sm="12">
			
		</b-col>
		<b-col md="12" lg="4" sm="12">
			<AppointmentStatus/>
		</b-col>
	</b-row>
  </div>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import Statistics from './Statistics.vue'
import Appointment from './Appointment.vue'
import BestGarages from './BestGarages.vue'
import BestServices from './BestServices.vue'
import AppointmentStatus from './AppointmentStatus.vue'

export default {
  components: {
	BRow,
	BCol,
    Statistics,
    Appointment,
    BestGarages,
    BestServices,
    AppointmentStatus,
  },
}
</script>