
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="garageName"/>
		<Garage :garage="garage" v-if="garage"/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import Garage from "./components/garage/Garage.vue"
import store from '@/store'

export default {
  name: "GaragePage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    Garage,
  },
  data() {
    return {
	  garage: null,
	  garageName: '',
    }
  },
  created() {
	this.$store.dispatch('garage-module/getGarageByTechnician').then(() => {
		this.garage = store.getters['garage-module/garageByTechnician']
		this.garageName = this.garage.name
	})
  }
}
</script>