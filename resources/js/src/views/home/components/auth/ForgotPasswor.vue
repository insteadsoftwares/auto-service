<template>
  <div>
    <b-modal id="forgot-password-modal" title="Réinitialiser le mot de passe" hide-footer backdrop modal-class="custom-backdrop">
		<validation-observer ref="forgotValidation" #default="{ handleSubmit }">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- email -->
              <b-form-group >
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required|email"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="UserIcon" />
					</b-input-group-prepend>
					<b-form-input
						id="login-email"
						v-model="forgotEmail"
						:state="errors.length > 0 ? false:null"
						name="login-email"
						placeholder="Email"
					/>
    			  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- submit buttons -->
              <b-button
                type="submit"
                block
                @click="validationForm"
				class="submit-btn"
              >
                Envoyer le lien
              </b-button>
            </b-form>
		</validation-observer>
	</b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { BFormGroup, BFormInput, BForm, BButton, BInputGroup, BInputGroupAppend, BInputGroupPrepend } from 'bootstrap-vue'
import { required, email } from '@validations'

export default {
  name: 'ForgotPasswordModal',
  components: {
    BFormGroup,
    BFormInput,
    BForm,
    BButton,
    BInputGroup,
    BInputGroupAppend,
    BInputGroupPrepend,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      forgotEmail: '',
	  required,
	  email
    }
  },
  methods: {
	validationForm() {
		this.$refs.forgotValidation.validate().then(success => {
			if (success) {
				this.sendResetLink()
			}
		})
	},
    async sendResetLink() {
        try {
          const response = await this.$store.dispatch('auth-module/forgotPassword', { email: this.forgotEmail })

          this.$bvModal.hide('forgot-password-modal')
		  this.$toast.success('Vérifiez votre email pour réinitialiser votre mot de passe.')
        } catch (err) {
			this.$toast.error(err.response?.data?.message || 'Impossible d’envoyer le lien')
        }
    }
  }
}
</script>

<style>
@import '../../../../../../../public/homePage/css/auth.css';
</style>
