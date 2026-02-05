<template>
  <div v-if="statistics">
    <b-row>
      <b-col
        xl="3"
        md="3"
        sm="6"
        xm="12"
        v-for="item in cards"
        :key="item.label"
      >
        <b-card
          class="kpi-card"
          :class="item.borderClass"
        >
          <div class="d-flex align-items-center mb-1">
			<b-avatar
				:variant="item.variant"
				size="48"
				class="mb-1"
			>
				<feather-icon
				:icon="item.icon"
				size="24"
				/>
			</b-avatar>

			<h1 class="font-weight-bolder mb-0 ml-1" style="white-space: nowrap;">
				{{ item.value }}
			</h1>
		  </div>	

          <b-card-text>
            {{ item.label }}
          </b-card-text>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import {
  BCard,
  BCardText,
  BRow,
  BCol,
  BAvatar
} from 'bootstrap-vue'
import store from '@/store'

export default {
  name: 'StatisticsCards',
  components: {
    BCard,
    BCardText,
    BRow,
    BCol,
    BAvatar
  },
  data() {
    return {
      statistics: null
    }
  },
  computed: {
    cards() {
      if (!this.statistics) return []

      return [
        {
          label: 'Nb garages',
          value: this.statistics.garages,
          icon: 'BoxIcon',
          variant: 'light-primary',
          borderClass: 'border-primary'
        },
        {
          label: 'Nb clients',
          value: this.statistics.clients,
          icon: 'UsersIcon',
          variant: 'light-danger',
          borderClass: 'border-danger'
        },
        {
          label: 'Nb services',
          value: this.statistics.services,
          icon: 'SettingsIcon',
          variant: 'light-info',
          borderClass: 'border-info'
        },
        {
          label: 'Nb RDV effectué',
          value: this.statistics.completedAppointments,
          icon: 'CalendarIcon',
          variant: 'light-success',
          borderClass: 'border-success'
        }
      ]
    }
  },
  created() {
    store.dispatch('statistic-module/getStatistics').then(() => {
      this.statistics = store.getters['statistic-module/statistics']
    })
  }
}
</script>
<style scoped>
.kpi-card {
  border-radius: 6px;
  border-bottom: 3px solid transparent;
  box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.08);
}

/* Couleurs identiques à Vuexy */
.border-primary {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
  border-bottom: 2px solid #7367f0 !important;
}

.border-danger {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
  border-bottom: 2px solid #ea5455 !important;
}

.border-success {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
  border-bottom: 2px solid #28c76f !important;
}

.border-info {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
  border-bottom: 2px solid #00cfe8 !important;
}
</style>
