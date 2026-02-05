<template>
  <b-card no-body class="card-browser-states">
    <b-card-header>
      <b-card-title>Rendez vous</b-card-title>
    </b-card-header>
	
	<b-card-body class="statistics-body" style="padding-bottom: 20px" v-if="appointmentStatistics">
	  <AppointmentStatisticsChart :series="[ratioAppointment]" :color="amountAppointmentColor"/>
	  <b-row class="text-center mx-0">
		<b-col cols="6" class="border-top border-right d-flex align-items-between flex-column py-1">
			<b-card-text class="text-muted mb-0">
				Effectué
			</b-card-text>
			<h3 class="font-weight-bolder mb-0">
				{{ appointmentStatistics.completedAppointments }}
			</h3>
		</b-col>

		<b-col cols="6" class="border-top d-flex align-items-between flex-column py-1">
			<b-card-text class="text-muted mb-0">
				Réservés
			</b-card-text>
			<h3 class="font-weight-bolder mb-0">
				{{ appointmentStatistics.totalAppointments }}
			</h3>
		</b-col>
	  </b-row>
	</b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardText, BCardHeader, BCardTitle, BCardBody, BRow, BCol } from 'bootstrap-vue'
import AppointmentStatisticsChart from './charts/AppointmentStatisticsChart.vue'
import store from '@/store'

export default {
    components: {
      BCard,
      BCardText,
      BCardHeader,
      BCardTitle,
      BCardBody,
      BRow,
      BCol,
      AppointmentStatisticsChart
    },
    data() {
      return {
        amountAppointmentColor: '',
        ratioAppointment: null,
        appointmentStatistics: null,
      }
    },
    created() {
	  store.dispatch('statistic-module/getAppointmentStatistics').then(() => {
		this.appointmentStatistics = store.getters['statistic-module/appointmentStatistics']	
		this.ratioAppointment = this.appointmentStatistics.totalAppointments != 0 ? ((this.appointmentStatistics.completedAppointments / this.appointmentStatistics.totalAppointments) * 100).toFixed(2) : 0

		if(this.ratioAppointment <= 33) this.amountAppointmentColor = '#ff0000'
		else if(this.ratioAppointment > 33 && this.ratioAppointment <= 66) this.amountAppointmentColor = '#e5a851'
		else this.amountAppointmentColor = '#51e5a8'	
	  })
    }
}
</script>
