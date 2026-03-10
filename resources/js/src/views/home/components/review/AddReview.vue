<template>
  <b-modal
    id="review-modal"
    title="Donner votre avis"
    hide-footer
  >
    <div class="text-center mb-3">
      <span
        v-for="star in 5"
        :key="star"
        @click="setRating(star)"
        :class="['star', { active: star <= rating }]"
      >
        ★
      </span>
    </div>

    <textarea
      v-model="comment"
      class="form-control"
      placeholder="Votre commentaire"
    ></textarea>

    <div class="mt-3 text-right">
      <button class="btn btn-secondary mr-2" @click="close">
        Annuler
      </button>

      <button
        class="btn btn-send"
        :disabled="rating === 0"
        @click="submitReview"
      >
        Envoyer
      </button>
    </div>

  </b-modal>
</template>

<script>
import { integer } from 'vee-validate/dist/rules'
export default {
  props: {
    appointmentId: {
		type: integer,
		default: null,
	},
  },
  data() {
    return {
      rating: 0,
      comment: ""
    }
  },
  methods: {
    setRating(star) {
      	this.rating = star
    },
    close() {
		this.$bvModal.hide('review-modal')
		this.reset()
    },
    reset() {
		this.rating = 0
		this.comment = ""
    },
    submitReview() {
		const payload = {
  			appointment_id: this.appointmentId,
			rating: this.rating,
			comment: this.comment,
		}

		this.$store.dispatch("review-module/addReview", payload).then(() => {
			this.close()
        })
    }
  }
}
</script>

<style scoped>
.btn-send{
	background: #F35C27;
    border-color: #F35C27;
	color: white;
}
</style>