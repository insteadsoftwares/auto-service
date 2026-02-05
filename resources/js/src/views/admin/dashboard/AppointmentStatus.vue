<template>
    <b-card no-body>
        <b-card-header>
            <b-card-title>État des rendez-vous</b-card-title>
        </b-card-header>

        <b-card-body>
            <AppointmentStatusChart :chartData="appointmentsStatusInfo.chartData"/>
        </b-card-body>
    </b-card>
</template>

<script>
  import { BCard, BCardText, BCardHeader, BCardTitle, BCardBody, BRow, BCol } from 'bootstrap-vue'
  import AppointmentStatusChart from './charts/AppointmentStatusChart.vue'

  export default {
    components: {
      BCard,
      BCardText,
      BCardHeader,
      BCardTitle,
      BCardBody,
      BRow,
      BCol,
      AppointmentStatusChart
    },
    data() {
        return {
            appointmentsStatusInfo: Object,
        }
    },
    created() {
        this.$store.dispatch('statistic-module/getAppointmentsStatus').then(() => {
            const appointmentsStatus = this.$store.getters['statistic-module/appointmentsStatus']
            this.appointmentsStatusInfo = []
            const labels = ['Annulé', 'Terminé', 'Confirmé', 'En attente']
			const data = [appointmentsStatus.cancelledAppointments, appointmentsStatus.completedAppointments,
				appointmentsStatus.confirmedAppointments, appointmentsStatus.pendingAppointments]
			this.appointmentsStatusInfo = {
				appointmentsStatusList: [
					{ name: 'Annulé', quantity: appointmentsStatus.cancelledAppointments },
					{ name: 'Terminé', quantity: appointmentsStatus.completedAppointments },
					{ name: 'Confirmé', quantity: appointmentsStatus.confirmedAppointments },
					{ name: 'En attente', quantity: appointmentsStatus.pendingAppointments }
				],
				chartData: {
					labels: labels,
					data: data
				}
			}
        })
    }
  }
</script>
