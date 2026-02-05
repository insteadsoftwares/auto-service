
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Véhicule'"/>
		<Vehicle/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import Vehicle from "./components/vehicle/Vehicle.vue"

export default {
  name: "VehiclePage",
  components: {
    HeaderTop,
    HeaderInternal,
    BreadCrumb,
    Vehicle,
  },
  setup() {
    // 
  },
}
</script>