<template>
  <div class="testimonial-area gray-bg section-padding" v-if="reviews.length > 0">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-title">
            <h2>Témoignages de <b>clients satisfaits</b> <br>
              et heureux de notre service</h2>
          </div>
        </div>
      </div>

      <div class="testimonial-carousel owl-carousel">
		<div class="single-team-item" v-for="review in reviews" :key="review.id">
			<div class="testimonial-header">
				<div class="client-info">
					<h5>{{ review.client.first_name }} {{ review.client.last_name }}</h5>
				</div>
				<div class="rating">
					<span
						v-for="star in 5"
						:key="star"
						:class="['star', { active: star <= review.rating }]"
					>
						★
					</span>
				</div>
			</div>
			<div class="garage">
				<strong>Garage :</strong> 
				<span v-if="review.appointment.garage">{{ review.appointment.garage.name }}</span>
				<span v-else>-</span>
			</div>
			<div class="service">
				<strong>Service :</strong> 
				<span v-if="review.appointment.service">{{ review.appointment.service.name }}</span>
				<span v-else>Service supprimé</span>
			</div>
			<p class="comment">
				{{ review.comment }}
			</p>
		</div>
      </div>
    </div>
  </div>
</template>

<script>
import store from '@/store'

export default {
  name: "TestimonialCarousel",
  data() {
    return {
      reviews: []
    };
  },
  methods: {
	initCarousel(){
		$('.testimonial-carousel').owlCarousel({
			items: 1,
			margin: 30,
			dots: true,
			nav: false,
			loop: false,
			autoplay: true,
			responsiveClass: true,
			responsive: {
				575: { items: 1 },
				767: { items: 1 },
				990: { items: 2 },
				1200: { items: 2 },
			}
		});
	}
  },
  created() {
	store.dispatch('review-module/getReviews').then(() => {
		this.reviews = store.getters['review-module/reviews'].nodes
		
		this.$nextTick(() => {
			this.initCarousel()
		});
	})
  }
}
</script>

<style>
.testimonial-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:10px;
}

.rating i{
  color:#ff0000;
  font-size:18px;
}

.garage,
.service{
  font-size:14px;
  margin-bottom:5px;
}

.comment{
  margin-top:10px;
  margin-bottom: 0;
  height: 70px;
  color:#555;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>