<template>
    <b-card no-body>
      <b-card-header>
		<b-card-title class="mb-1 w-100">
		  <b-row class="w-100">
            <b-col cols="8">
              <b-card-title class="mb-1">
				Six meilleures garages
              </b-card-title>
            </b-col>

            <b-col cols="4">
              <div class="float-right">
                <b-dropdown :text="serviceSelected.name" size="sm" class="budget-dropdown" variant="outline-primary" right placement="bottom-end" no-flip>
                  <b-dropdown-item v-for="service in services" :key="service.id" @click="getSixBestGarages(service.id, service.name)">
                      {{ service.name }}
                  </b-dropdown-item>
                </b-dropdown>
              </div>
            </b-col>
          </b-row>
		</b-card-title>
      </b-card-header>
      <b-card-body> 
        <b-row class="pb-50">
            <b-col cols="7" class="d-flex justify-content-between flex-column text-right">
              <BestGaragesChart v-if="garagesInfo.chartData != null" :garagesInfo="garagesInfo.chartData"/>
            </b-col>
            <b-col offset-xl="1" cols="4" class="d-flex justify-content-around align-items-center mt-1">
              <b-row>
                <b-col cols="6" class="d-flex align-items-baseline" style="height: 100px;" v-for="data in garagesInfo.garagesList" :key="data.name">
                  <span class="bullet svg-font-small-3 mr-50 cursor-pointer" :class="`${data.color}`"/>
                  <div>
                    <p class="mb-0">{{ data.name }}</p>
                    <h5>{{ data.quantity }}</h5>
                  </div>
                </b-col>
              </b-row>
            </b-col>
        </b-row>
      </b-card-body>
    </b-card>
</template>
  
<script>
import { BCard, BCardText, BCardHeader, BCardTitle, BCardBody, BRow, BCol, BDropdown, BDropdownItem } from 'bootstrap-vue'
import BestGaragesChart from './charts/BestGaragesChart.vue'

export default {
  components: {
    BCard, 
    BCardText, 
    BCardHeader, 
    BCardTitle, 
    BCardBody,
    BRow,
    BCol, 
	BDropdown, 
	BDropdownItem,
    BestGaragesChart
  },  data() {
    return {
	  sixBestGarages: [],
      garagesInfo: {garagesList: [], chartData: {labels: [], data: []}},
      colors: ['bullet-primary', 'bullet-info', 'bullet-success', 'bg-yellow', 'bullet-danger', 'bullet-warning'],
	  services: [{id: 0, name: 'Tous les services'}],
	  serviceSelected: {id: 0, name: 'Tous les services'},
	}
  },
  methods: {
	getStatisticsReportInfo(){
      this.garagesInfo = []
      let garagesList = []
      let labels = []
      let data = []
      let index = 0
      this.sixBestGarages.forEach(line => {
        labels.push(line.garage.name)
        data.push(Number(line.appointments_nb))
        garagesList.push({name: line.garage.name, quantity: Number(line.appointments_nb), color: this.colors[index]})
        index++
      })
      this.garagesInfo = {garagesList: garagesList, chartData: {labels: labels, data: data}}
	},
	getSixBestGarages(Service_id, name){
		this.serviceSelected = {id: Service_id, name: name}
		this.$store.dispatch('statistic-module/getSixBestGarages', { service_id: this.serviceSelected.id }).then(() => {
			this.sixBestGarages = this.$store.getters['statistic-module/sixBestGarages']
			this.getStatisticsReportInfo()
		})
	}
  },
  created() {
	this.getSixBestGarages(this.serviceSelected.id, this.serviceSelected.name)
	this.$store.dispatch('service-module/getService').then(() => {
		const servicesFromStore = this.$store.getters['service-module/services'].nodes
		this.services = [{ id: 0, name: 'Tous les services' }, ...servicesFromStore]
	})
  }
}
</script>

<style scoped>
  .align-items-center {
    align-items: center !important;
  }
  .justify-content-around {
      justify-content: space-around !important;
  }
  .bg-yellow{
    background: #ffe802;
  }
</style>