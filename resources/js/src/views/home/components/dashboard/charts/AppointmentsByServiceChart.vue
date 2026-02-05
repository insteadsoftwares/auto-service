<template>
    <div class="mt-3" style="overflow-x: scroll; overflow-y: hidden; width: 100%;">
      <vue-apex-charts ref="categoriesChart" type="bar" height="385" :width="chartWidth" :options="dataChart.chartOptions" :series="dataChart.series"/>
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
      chartWidth: '100%',
      dataChart: {
        series: [],
        chartOptions: {
          chart: {
            toolbar: { show: false },
            zoom: {
              enabled: false 
            },
          },
          plotOptions: {
            bar: {
              columnWidth: '50%',
              dataLabels: {
                position: 'top', 
              },
            }
          },
          colors: ['#7367f0'],
          dataLabels: {
            enabled: true,
            formatter: this.createTooltipFormatter(),
            offsetY: -20,
            style: {
              fontSize: '12px',
              colors: ["#5e5873"]
            }
          },
          xaxis: {
            categories: [],
            axisBorder: {
              show: true
            },
            labels: {
              hideOverlappingLabels: true,
              trim: true,
              rotation: 0,
              rotateAlways: false,
            },
          },
          yaxis: {
            labels: {
              show: false,
              formatter: this.createTooltipFormatter()
            }
          },
          grid: {
            borderColor: '#e9ecef',
            strokeDashArray: 5,
            xaxis: {
              lines: {
                show: false,
              },
            },
            yaxis: {
              lines: {
                show: true
              }
            },
          },
        },
      },
    }
  },
  methods: {
    updateSeries(newVal) {
      this.dataChart.series = [{name: 'Nombre de RDV', data: newVal.data}]
      this.dataChart.chartOptions.xaxis.categories = newVal.categories
      this.$refs.categoriesChart.updateOptions(this.dataChart.chartOptions, true);
      this.getChartWidth(newVal.categories.length)
    },
    createTooltipFormatter(){
      return (value) => {
        return value;
      };
    },
    getChartWidth(categoriesLength){
    //   const windowWidth = window.innerWidth
    //   let maxNumber = 0
    //   if(windowWidth > 991) maxNumber = 4
    //   else maxNumber = 5

    //   if(categoriesLength >= maxNumber) this.chartWidth = categoriesLength * 100
    //   else this.chartWidth = '100%'
    }
  },
}
</script>

<style>
  /* .label-ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%; 
  } */
</style>