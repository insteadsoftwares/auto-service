<template>
	<div class="bg-white">
		<HeaderTop />
		<Header />
		<Hero />
		<About />
		<Services />
		<Feature />
		<Counter />  
		<Login />
		<Register />
		<ForgotPasswor />
		<ResetPassword />
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import Header from "./components/header/Header.vue"
import Hero from "./components/Hero.vue"
import About from "./components/About.vue"
import Services from "./components/Services.vue"
import Feature from "./components/Feature.vue"
import Counter from "./components/Counter.vue"
import Login from "./components/auth/Login.vue";
import Register from "./components/auth/Register.vue";
import ForgotPasswor from "./components/auth/ForgotPasswor.vue";
import ResetPassword from "./components/auth/ResetPassword.vue";


export default {
  name: "HomePage",
  components: {
    HeaderTop,
    Header,
    Hero,
    About,
    Services,
    Feature,
    Counter,
	Login,
    Register,
    ForgotPasswor,
	ResetPassword,
  },
  setup() {
    //
  },
  mounted() {
    if (this.$route.query.token && this.$route.query.email) {
		this.$bvModal.show('reset-password-modal')
	}
  },

}
</script>
