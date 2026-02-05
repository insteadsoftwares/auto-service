<template>
  <div>
    <router-view />
    <PreLoader />
    <FooterArea />
    <FooterBottom />
  </div>
</template>

<script>
import PreLoader from "../../views/home/components/PreLoader.vue"
import FooterArea from "../../views/home/components/footer/FooterArea.vue"
import FooterBottom from "../../views/home/components/footer/FooterBottom.vue"

export default {
  name: "MinimalLayout",
  components: {
    PreLoader,
    FooterArea,
    FooterBottom,
  },
  mounted() {
    document.body.classList.remove('dark-layout')
  },
};
</script>
