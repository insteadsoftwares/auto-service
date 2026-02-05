
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Espace client'"/>
		<ClientSpace/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import ClientSpace from "./components/ClientSpace.vue"

export default {
  name: "ClientSpacePage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    ClientSpace,
  },
  setup() {
    // 
  },
}
</script>