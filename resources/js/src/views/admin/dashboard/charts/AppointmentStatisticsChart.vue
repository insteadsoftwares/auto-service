<template>
  <div>
    <vue-apex-charts type="radialBar" height="245" class="my-2" :options="chartOptions" :series="series"/>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  components: {
    VueApexCharts
  },
  props: {
    series: Array,
    color: String,
  },
  data() {
    return {
        // series: [70],
      chartOptions: {
        chart: {
          sparkline: {
            enabled: true,
          },
          dropShadow: {
            enabled: true,
            blur: 3,
            left: 1,
            top: 1,
            opacity: 0.1,
          },
        },
        colors: [this.color],
        plotOptions: {
          radialBar: {
            offsetY: -10,
            startAngle: -150,
            endAngle: 150,
            hollow: {
              size: '77%',
            },
            track: {
              background: '#ebe9f1',
              strokeWidth: '50%',
            },
            dataLabels: {
              name: {
                show: false,
              },
              value: {
                color: '#5e5873',
                fontSize: '2.86rem',
                fontWeight: '600',
              },
            },
          },
        },
        stroke: {
          lineCap: 'round',
        },
        grid: {
          padding: {
            bottom: 30,
          },
        },
      },
    }
  },
  computed: {

  },
}
</script>
