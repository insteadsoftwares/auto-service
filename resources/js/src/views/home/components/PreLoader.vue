<template>
  <div id="loader">
    <div class="loading">
      <div />
    </div>
  </div>    
</template>

<script>
import Vue from "vue"
import { setup, onMounted } from "@vue/composition-api"

export default {
  name: "Loader",
  setup() {
    onMounted(() => {
      setTimeout(function () {
        $("#loader").fadeOut(200)
      }, 200)

      $(window).on("load", function () {
        $(".site-preloader-wrap, .slide-preloader-wrap").fadeOut(1000)
      })
    })
  },
}
</script>
