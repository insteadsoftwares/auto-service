<template>
    <div class="mt-3">
      <vue-apex-charts ref="threeBestSellersChart" type="donut" height="280" :options="doughnutChart.chartOptions" :series="doughnutChart.series"/>
    </div>
</template>
  
  
<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  components: {
    VueApexCharts
  },
  props: {
    chartData: Object,
  },
  watch: { 
    chartData: function(newVal){
      this.updateSeries(newVal)
    },
  },
  data() {
    return {
      doughnutChart: {
        series: [],
        chartOptions: {
          labels: [],
          legend: {
			show: false,
		  },
		  stroke: {
			show: false
		  },
		  dataLabels: {
			enabled: false
		  },
          tooltip: {
			custom: ({ series, seriesIndex, dataPointIndex, w }) => {
				const name = w.globals.labels[seriesIndex]
				const value = series[seriesIndex]

				return `<div class="custom-tooltip">
							<div class="tooltip-name">${name}</div>
							<div class="tooltip-value">Nombre de RDV : ${value}</div>
						</div>`
			}
          }
        },
      },
    }
  },
  methods: {
    updateSeries(newVal) {
      this.doughnutChart.series = newVal.data
      this.doughnutChart.chartOptions.labels = newVal.labels
      this.$refs.threeBestSellersChart.updateOptions(this.doughnutChart.chartOptions, true);
    },
  },
}
</script>