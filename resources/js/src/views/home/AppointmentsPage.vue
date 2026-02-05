
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Mes Rendez-vous'"/>
		<Appointments/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import Appointments from "./components/appointment/Appointments.vue"

export default {
  name: "AppointmentsPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    Appointments,
  },
  setup() {
    // 
  },
}
</script>