<template>
  <div class="achievement-area">
    <div class="overlay-2" />
    <div class="container">
      <div class="row">
        <div
          v-for="cn in counter.slice(0,4)"
          :key="cn.id"
          class="col-lg-3 col-md-6 col-sm-6"
        >
          <div class="single-counter-box">
            <div class="fact-icon">
              <i :class="cn.icon" />
            </div>
            <p class="counter-number">
              <span>{{ cn.number }}</span>
            </p>
            <h6>{{ cn.title }}</h6>
          </div>
        </div> 				
      </div>
    </div>
  </div>
</template>

<script>
import store from '@/store'

export default {
  name: "AchievementArea",
  data() {
    return {
      counter: [],
	  statistics: null
    };
  },
  mounted() {
    // Exemple de données statiques
    this.counter = [
      {
        id: "1",
        icon: "flaticon-helmet",
        number: "0",
        title: "Rendez-vous effectué", 
    	key: "completedAppointments"
      },
      {
        id: "2",
        icon: "flaticon-plumber",
        number: "0",
        title: "Garages partenaires",
		key: "garages"
      },
      {
        id: "3",
        icon: "flaticon-engineer",
        number: "0",
        title: "Services proposés",
		key: "services"
      },
      {
        id: "4",
        icon: "flaticon-process",
        number: "0",
        title: "Services proposés",
      }
    ];

    // Initialisation du compteur après rendu du DOM
    // this.$nextTick(() => {
    //   $(".counter-number span").counterUp({
    //     delay: 10,
    //     time: 1000,
    //   });
    // });
  },
  created() {
	store.dispatch('statistic-module/getStatistics').then(() => {
		this.statistics = store.getters['statistic-module/statistics']
		this.counter = this.counter.map(item => ({ ...item, number: this.statistics[item.key] ?? 0 }))
		this.$nextTick(() => {
			$(".counter-number span").counterUp({
				delay: 10,
				time: 1000,
			})
		})
	})
  }
};
</script>
