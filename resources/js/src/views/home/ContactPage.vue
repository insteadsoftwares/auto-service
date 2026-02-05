
<template>
	<div class="bg-white">
		<HeaderTop />
		<HeaderInternal/>
		<BreadCrumb :currentPageTitle="'Contact'"/>
		<div id="contact-us" class="contact-us-area section-padding">
			<div class="container">
				<div class="row">
					<div class="offset-lg-2 col-lg-8 text-center">
						<div class="section-title">
							<h2>N'hésitez pas à nous contacter pour obtenir des informations</h2>
						</div>
					</div>
				</div>
				<div class="contact-us-wrapper">
					<div class="row gx-0">
						<div class="col-lg-3 col-md-6 col-12">
							<div class="contact-us-inner">
								<div class="info-i"><span><i class="las la-map-marker"></i></span></div>
								<h5>Adresse</h5>
								<p>66 Broklyn Streat <br>Tunis</p>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="contact-us-inner">
								<div class="info-i"><span><i class="las la-clock"></i></span></div>
								<h5>Horaires d’ouverture</h5>
								<p>Lundi – Vendredi : 9h – 18h<br>Samedi : 10h – 14h</p>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="contact-us-inner">
								<div class="info-i"><span><i class="las la-mobile"></i></span></div>
								<h5>Téléphone</h5>
								<p>+216 58 476 718 <br>+216 95 976 267</p>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="contact-us-inner">
								<div class="info-i"><span><i class="las la-envelope"></i></span></div>
								<h5>E-mail</h5>
								<p>auto.service@gmail.com</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="contact-page" class="contact-section bg-cover section-padding">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12 text-center wow fadeInRight">
						<div class="contact-form-wrapper mt-100">
							<div class="section-title">
								<h2>Contactez-nous</h2>
							</div>
							<div class="contact-form">
								<form @submit.prevent="submitForm()">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-12">
											<input type="text" placeholder="Nom" v-model="form.name" required>
										</div>
										<div class="col-lg-6 col-md-6 col-12">
											<input type="email" placeholder="E-mail" v-model="form.email" required>
										</div>
										<div class="col-lg-6 col-md-6 col-12">
											<input type="tel" placeholder="Téléphone" v-model="form.phone">
										</div>
										<div class="col-lg-6 col-md-6 col-12">
											<input type="text" placeholder="Object" v-model="form.subject">
										</div>
										<div class="col-lg-12">
											<textarea name="message" id="message" cols="30" rows="10" placeholder="Message" v-model="form.message" required></textarea>
										</div>
										<div class="col-lg-12 col-md-6 col-12 text-center">
											<button class="main-btn mt-30" type="submit">Envoyer un message</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Vue from "vue"
import HeaderTop from "./components/header/HeaderTop.vue"
import HeaderInternal from "./components/header/HeaderInternal.vue"
import BreadCrumb from "./components/BreadCrumb.vue"
import store from '@/store'

export default {
  name: "ContactPage",
  components: {
	HeaderTop,
    HeaderInternal,
    BreadCrumb,
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        phone: "",
        subject: "",
        message: "",
      }
    }
  },
  methods: {
    async submitForm() {
		try {
			await this.$store.dispatch('contact-module/contact', this.form)
			this.resetForm()
			this.$toast.success('Message envoyé avec succès')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
  	},
	resetForm(){
		this.form = {
			name: "",
			email: "",
			phone: "",
			subject: "",
			message: "",
		}
  	}
  },
}
</script>