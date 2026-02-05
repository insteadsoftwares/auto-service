<template>
  <div class="breadcroumb-area bread-bg">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcroumb-title text-center">
            <h1>{{ currentPageTitle }}</h1>
            <!-- <h6>
              <router-link :to="{ name: 'HomePage' }">Home</router-link> / {{ currentPageTitle }}
            </h6> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Breadcrumb",
  props: {
	currentPageTitle: {
		type: String,
		required: true
	},
  },
};
</script>
