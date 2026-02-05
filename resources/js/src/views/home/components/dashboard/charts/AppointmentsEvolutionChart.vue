<template> 
  <div>
    <vue-apex-charts 
      height="450" 
      :options="computedChartOptions" 
      :series="statisticsProfit.series"
    />
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  components: { VueApexCharts },
  props: {
    chartData: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      categories: [],
      statisticsProfit: {
        series: [{
          name: 'Nombre de RDV',
          data: []
        }],
        chartOptions: {
          chart: {
            type: 'line',
            toolbar: { show: false },
            zoom: { enabled: false }
          },
          grid: {
            borderColor: '#e0e0e0',
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: true } },
            padding: { top: 10, right: 10, bottom: 10, left: 10 }
          },
          stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#1E90FF']
          },
          markers: {
            size: 6,
            colors: ['#1E90FF'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: { size: 8 }
          },
          xaxis: {
            categories: [],
            labels: {
              show: true,
              style: {
                colors: '#666',
                fontSize: '14px'
              }
            },
            axisBorder: { show: true, color: '#ccc' },
            axisTicks: { show: true, color: '#ccc' }
          },
          yaxis: {
            labels: {
              formatter: val => Math.round(val).toString(),
              style: {
                colors: '#666',
                fontSize: '14px'
              }
            },
            min: 0
          },
          tooltip: {
			enabled: true,
			x: {
				show: true,
				formatter: (val, { dataPointIndex }) => {
				return this.categories[dataPointIndex] || val
				}
			},
			y: {
				formatter: val => Math.round(val).toString()
			}
		  }
        }
      }
    }
  },
  computed: {
    computedChartOptions() {
      return {
        ...this.statisticsProfit.chartOptions,
        xaxis: {
          ...this.statisticsProfit.chartOptions.xaxis,
          categories: this.categories
        }
      }
    }
  },
  watch: {
    chartData(newVal) {
      this.updateSeries(newVal)
    }
  },
  methods: {
    updateSeries(newVal) {
      this.statisticsProfit.series = [{
        name: 'Nombre de RDV',
        data: newVal.map(item => item.total_rdv)
      }]
      this.categories = newVal.map(item => 
        new Date(item.appointment_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
      )
    }
  }
}
</script>
