<template>
    <div class="mt-3" style="margin-bottom: 50px;">
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
          legend: { show: false },
          tooltip: {
            y: {
              formatter: function(value, series) {
                return value + ' %';
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