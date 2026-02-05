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
          colors: ['#28c76f', '#29a967', '#2b8c60', '#2c6e58'],
          labels: [],
          legend: {
			show: true,
			position: 'bottom',
			horizontalAlign: 'center',
			labels: {
			  colors: '#ffffff'
			},
			itemMargin: {
			  horizontal: 10,
			  vertical: 5
			}
		  },
		  stroke: {
			show: false
		  },
		  dataLabels: {
			enabled: false
		  },
          tooltip: {
            y: {
              formatter: function(value, series) {
                return value;
              }
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