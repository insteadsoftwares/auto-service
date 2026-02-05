<template>
  <div class="mt-4" id="dashboard-garage">
	<div class="dashboard-frame">
		<div class="mb-4">
			<h4>3 Meilleures clients</h4>
		</div>		
        <b-row class="pb-50">
			<b-col md="7" sm="12">
              <ThreeBestClientsChart :chartData="clinetsInfo.chartData"/>
            </b-col>
            <b-col md="5" sm="12" class="d-flex flex-column justify-content-center">
                <div class="d-flex align-items-baseline" style="height: 65px;" v-for="data in clinetsInfo.clientsList" :key="data.name">
                  <span class="bullet" :class="`${data.color}`"/>
                  <div>
                    <h6 class="mb-0">{{ data.name }}</h6>
                    <p>Nombre de RDV : {{ data.appointment_number }}</p>
                  </div>
                </div>
            </b-col>
		</b-row>
	</div>
  </div>
</template>

<script>
import store from '@/store'
import { BRow, BCol } from 'bootstrap-vue'
import ThreeBestClientsChart from './charts/ThreeBestClientsChart.vue'

export default {
  name: "ThreeBestClients",
  components: {
	BRow,
	BCol,
	ThreeBestClientsChart,
  },
  data() {
    return {
	  threeBestClients: [],
	  chartData: null,
	  clinetsInfo: {clientsList: [], chartData: {labels: [], data: []}},
      colors: ['bullet-primary', 'bullet-info', 'bullet-success'],
    };
  },
  created() {
	store.dispatch('statistic-module/getThreeBestClients', { start_date: this.startDate, end_date: this.endDate }).then(() => {
		this.threeBestClients = store.getters['statistic-module/threeBestClients']
		let labels = []
		let data = []
		let clientsList = []
      	let index = 0
		this.threeBestClients.forEach(line => {
			labels.push(line.client.first_name + ' ' + line.client.last_name)
			data.push(Number(line.total_rdv))
			clientsList.push({name: line.client.first_name + ' ' + line.client.last_name, appointment_number: Number(line.total_rdv), color: this.colors[index]})
			index++
		})
		this.clinetsInfo = {clientsList: clientsList, chartData: {labels: labels, data: data}}
	})
  }
};
</script>
<style>
.custom-tooltip {
  background: rgba(0, 0, 0, 0.75);
  color: #fff;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 13px;
  line-height: 1.3;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  max-width: 200px;
  white-space: normal;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.custom-tooltip .tooltip-name {
  font-weight: 600;
  margin-bottom: 4px;
  font-size: 14px;
}

.custom-tooltip .tooltip-value {
  font-weight: 400;
  font-size: 13px;
}

</style>