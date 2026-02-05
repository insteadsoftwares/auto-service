<template>
  <div class="question-section">
		<h6>Une question à nous poser ?</h6>
		<form @submit.prevent="submitQuestion">
			<input type="text" placeholder="Nom" v-model="form.name" required>
			<input type="email" placeholder="E-mail" v-model="form.email" required>
			<textarea cols="30" rows="10" placeholder="Comment pouvons-nous vous aider ?" v-model="form.message" required></textarea>
			<button type="submit" name="submit">Poser votre question</button>
		</form>
	</div>
</template>

<script>
import store from '@/store'

export default {
  components: {
    //
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        message: "",
      }
    }
  },
  methods: {
    async submitQuestion() {
		try {
			await this.$store.dispatch('contact-module/askQuestion', this.form)
			this.resetForm()
			this.$toast.success('Votre question a été envoyée avec succès')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
  	},
	resetForm(){
		this.form = {
			name: "",
			email: "",
			message: "",
		}
  	}
  }
}
</script>