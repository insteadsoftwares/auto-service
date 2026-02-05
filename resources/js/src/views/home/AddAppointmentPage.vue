
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Prendre Rendez-vous'"/>
		<AddAppointment/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import AddAppointment from "./components/appointment/AddAppointment.vue"

export default {
  name: "AddAppointmentPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    AddAppointment,
  },
  setup() {
    // 
  },
}
</script>