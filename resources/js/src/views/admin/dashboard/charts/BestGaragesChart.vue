<template>
    <div>
      <vue-apex-charts ref="bestGaragesChart" type="bar" height="370" :options="barChart.chartOptions" :series="barChart.series"/>
    </div>
</template>
  
<script>
import VueApexCharts from 'vue-apexcharts'
  
export default {
  components: {
    VueApexCharts
  },
  props: {
    garagesInfo: Object,
  },
  data() {
    return {
      barChart: {
        series: [{
          name: 'Quantité',
          data: this.garagesInfo.data
        }],
        chartOptions: {
          chart: {
            type: 'bar',
            height: 350,
            toolbar: {
              show: false,
            },
          },
          dataLabels: {
            formatter: function (val, opt) {
              return opt.w.globals.labels[opt.dataPointIndex]
            },
          },
          plotOptions: {
            bar: {
              barHeight: '70%',
              distributed: true,
              horizontal: true,
            }
          },
          xaxis: {
            categories: this.garagesInfo.labels,
            labels: {
              formatter: (val) => {
                return val
              },
              style: {
                colors: ['#6D6B77'],
              },
            },
          },
          yaxis: {
            labels: {
              show: false
            },
            labels: {
              show: false,
              formatter: function (val) {
                return val;
              }
            }
          },
          grid: {
            borderColor: '#e9ecef',
            strokeDashArray: 5,
            xaxis: {
              lines: {
                show: true,
              },
            },
            yaxis: {
              lines: {
                show: false
              }
            },
          },
          colors: ['#7367f0', '#00d1e9', '#28c76f', '#ffe802', '#ff4c51', '#ff9f43'],
          legend: {
            show: false
          }
        },
      },
    }
  },
  watch: {
    garagesInfo: {
      handler(newVal) {
        this.updateSeries(newVal)
      },
      deep: true
    }
  },
  methods: {
    updateSeries(newVal) {
      this.barChart.series[0].data = newVal.data
      this.barChart.chartOptions.xaxis.categories = newVal.labels
      this.$nextTick(() => {
        this.$refs.bestGaragesChart.updateOptions(this.barChart.chartOptions, true)
        this.$refs.bestGaragesChart.updateSeries(this.barChart.series, true)
      })
    }
  },
}
</script>