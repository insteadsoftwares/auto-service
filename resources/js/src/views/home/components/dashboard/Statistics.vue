<template>
  <div v-if="statistics">
	<b-row>
      <b-col xl="4" md="12" sm="12" class="mb-2">
		<div class="dashboard-card">
			<div class="dashboard-card-content">
				<div class="dashboard-stat-value">{{ statistics.completedAppointments }}</div>
				<div class="dashboard-stat-label">RDV Terminé</div>
			</div>
			<div class="dashboard-card-icon">
				<feather-icon icon="CalendarIcon" size="25" style="color: white"/>
			</div>
		</div>
      </b-col>
      <b-col xl="4" md="12" sm="12" class="mb-2">
		<div class="dashboard-card">
			<div class="dashboard-card-content">
				<div class="dashboard-stat-value">{{ statistics.pendingAppointments }}</div>
				<div class="dashboard-stat-label">RDV En attente</div>
			</div>
			<div class="dashboard-card-icon">
				<feather-icon icon="CalendarIcon" size="25" style="color: white"/>
			</div>
		</div>
      </b-col>
      <b-col xl="4" md="12" sm="12" class="mb-2">
		<div class="dashboard-card">
			<div class="dashboard-card-content">
				<div class="dashboard-stat-value">{{ statistics.upcomingAppointments }}</div>
				<div class="dashboard-stat-label">Prochain RDV</div>
			</div>
			<div class="dashboard-card-icon">
				<feather-icon icon="CalendarIcon" size="25" style="color: white"/>
			</div>
		</div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import store from '@/store'
import { BRow, BCol } from 'bootstrap-vue'

export default {
  name: "Statistics",
  components: {
	BRow,
	BCol
  },
  data() {
    return {
	  statistics: null
    };
  },
  created() {
	store.dispatch('statistic-module/getGarageStatistics').then(() => {
      this.statistics = store.getters['statistic-module/garageStatistics']
    })
  }
};
</script>