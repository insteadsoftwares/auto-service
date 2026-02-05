
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'À propos'"/>
		<About/>
		<Counter/>
		<ProcessSection/>
		<CTAOne/>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import About from "./components/About.vue"
import Counter from "./components/Counter.vue"
import ProcessSection from "./components/ProcessSection.vue"
import CTAOne from "./components/CTAOne.vue"

export default {
  name: "AboutPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
    About,
    Counter,
    ProcessSection,
    CTAOne,
  },
  setup() {
    // 
  },
}
</script>