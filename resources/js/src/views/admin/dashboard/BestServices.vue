<template>
    <b-card no-body>
        <b-card-header>
            <b-card-title>3 Meilleures services</b-card-title>
        </b-card-header>

        <b-card-body>
            <BestServicesChart :chartData="servicesInfo.chartData"/>
            <div v-for="(data, index) in servicesInfo.servicesList" :key="data.name" class="d-flex justify-content-between" :class="index === servicesInfo.servicesList.length - 1 ? 'mb-0':'mb-1'">
                <div class="series-info d-flex align-items-center">
                    <span class="font-weight-bold ml-75 mr-25">{{ data.name }}</span><span> - {{ data.ratio }}%</span>
                </div>
            </div>
        </b-card-body>
    </b-card>
</template>

<script>
  import { BCard, BCardText, BCardHeader, BCardTitle, BCardBody, BRow, BCol } from 'bootstrap-vue'
  import BestServicesChart from './charts/BestServicesChart.vue'

  export default {
    components: {
      BCard,
      BCardText,
      BCardHeader,
      BCardTitle,
      BCardBody,
      BRow,
      BCol,
      BestServicesChart
    },
    data() {
        return {
            servicesInfo: Object,
        }
    },
    created() {
        this.$store.dispatch('statistic-module/getThreeBestServices').then(() => {
            const threeBestServices = this.$store.getters['statistic-module/threeBestServices']
            this.servicesInfo = []
            let servicesList = []
            let labels = []
            let data = []
            let totalQuantity = threeBestServices.reduce((accumulator, currentValue) => accumulator + Number(currentValue.appointments_nb), 0);
            if(totalQuantity > 0){
                threeBestServices.forEach(line => {
                    const ratio = ((Number(line.appointments_nb) / totalQuantity) * 100).toFixed(2)
                    labels.push(line.service.name)
                    data.push(Number(ratio))
                    servicesList.push({name: line.service.name, quantity: Number(line.appointments_nb), ratio: ratio})
                })
            }
            this.servicesInfo = {servicesList: servicesList, chartData: {labels: labels, data: data}}
        })
    }
  }
</script>
