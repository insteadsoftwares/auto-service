
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Espace garage'"/>
		<GarageSpace/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import GarageSpace from "./components/GarageSpace.vue"

export default {
  name: "GarageSpacePage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    GarageSpace,
  },
  setup() {
    // 
  },
}
</script>