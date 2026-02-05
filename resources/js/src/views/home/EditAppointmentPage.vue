
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Modifier Rendez-vous'"/>
		<EditAppointment v-if="appointment" :appointmentData="appointment"/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import EditAppointment from "./components/appointment/EditAppointment.vue"
import store from '@/store'

export default {
  name: "EditAppointmentPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    EditAppointment,
  },
  setup() {
    // 
  },
  data() {
    return {
	  appointment: null,
    }
  },
  created() {
	this.$store.dispatch('appointment-module/getAppointmentById', this.$route.params.id).then(() => {
		this.appointment = store.getters['appointment-module/appointmentById']
	})
  }
}
</script>