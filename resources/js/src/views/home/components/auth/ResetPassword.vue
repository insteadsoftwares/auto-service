<template>
  <div>
    <b-modal
      id="reset-password-modal"
      title="Réinitialiser le mot de passe"
      hide-footer
      no-close-on-backdrop
      no-close-on-esc
      modal-class="custom-backdrop"
    >
      <validation-observer ref="resetValidation" #default="{ handleSubmit }">
        <b-form class="auth-login-form mt-2" @submit.prevent="handleSubmit(onSubmit)">

          <!-- Email -->
          <b-form-group class="mb-2">
            <validation-provider name="email" rules="required|email" #default="{ errors }">
			  <b-input-group class="custom-input">
				<b-input-group-prepend is-text>
					<feather-icon icon="UserIcon" />
				</b-input-group-prepend>
				<b-form-input
					id="reset-email"
					v-model="userEmail"
					:state="errors.length ? false : null"
					readonly
				/>
    		  </b-input-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

          <!-- Password -->
          <b-form-group class="mb-2">
            <validation-provider name="mot de passe" rules="required|min:6" #default="{ errors }">
              <b-input-group class="custom-input" :class="errors.length ? 'is-invalid' : null">
				<b-input-group-prepend is-text>
					<feather-icon icon="LockIcon" />
				</b-input-group-prepend>
                <b-form-input
                  id="reset-password"
                  v-model="password"
                  :state="errors.length ? false : null"
                  :type="passwordFieldType"
                  placeholder="Nouveau mot de passe"
                />
                <b-input-group-append is-text>
                  <feather-icon
                    :icon="passwordToggleIcon"
                    class="cursor-pointer"
                    @click="togglePasswordVisibility"
                  />
                </b-input-group-append>
              </b-input-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

          <!-- Confirm password -->
          <b-form-group >
            <validation-provider name="confirmation" rules="required|min:6" #default="{ errors }">
              <b-input-group class="custom-input" :class="errors.length ? 'is-invalid' : null">
				<b-input-group-prepend is-text>
					<feather-icon icon="LockIcon" />
				</b-input-group-prepend>
                <b-form-input
                  id="reset-password-confirm"
                  v-model="password_confirmation"
                  :state="errors.length ? false : null"
                  :type="passwordFieldType"
                  placeholder="Confirmer le mot de passe"
                />
                <b-input-group-append is-text>
                  <feather-icon
                    :icon="passwordToggleIcon"
                    class="cursor-pointer"
                    @click="togglePasswordVisibility"
                  />
                </b-input-group-append>
              </b-input-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

          <!-- Submit -->
          <b-button
            type="submit"
            block
            class="submit-btn"
            @click="validateForm"
          >
            Réinitialiser le mot de passe
          </b-button>

        </b-form>
      </validation-observer>

      <!-- Messages -->
      <div v-if="error" class="alert alert-danger mt-2">{{ error }}</div>
      <div v-if="success" class="alert alert-success mt-2">{{ success }}</div>

    </b-modal>
  </div>
</template>


<script>
import axios from 'axios'
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import { BFormGroup, BFormInput, BInputGroupAppend, BInputGroup, BForm, BButton, BInputGroupPrepend } from 'bootstrap-vue'

export default {
  name: "ResetPassword",
  components: {
	BFormGroup,
    BFormInput,
    BInputGroupAppend,
    BInputGroup,
    BForm,
    BButton,
    BInputGroupPrepend,
    ValidationObserver,
    ValidationProvider,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      userEmail: this.$route.query.email || '',
      token: this.$route.query.token,
      password: '',
      password_confirmation: '',
      error: '',
      success: '',
      required,
      email,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    validateForm() {
      this.$refs.resetValidation.validate().then((success) => {
        if (success) {
          this.onSubmit()
        }
      })
    },

    async onSubmit() {
      this.error = ''
      this.success = ''

      if (this.password !== this.password_confirmation) {
        this.error = "Les mots de passe ne correspondent pas."
        return
      }

      try {
        // await axios.post('http://127.0.0.1:8000/api/password/reset', {
        //   email: this.userEmail,
        //   password: this.password,
        //   password_confirmation: this.password_confirmation,
        //   token: this.token,
        // })

		const resetData = {
			email: this.userEmail,
			password: this.password,
			password_confirmation: this.password_confirmation,
			token: this.token,
		}

		const response = await this.$store.dispatch('auth-module/resetPassword', resetData)
		this.$bvModal.hide('reset-password-modal')
			this.$toast.success('Votre mot de passe a été réinitialisé')
      } catch (err) {
			this.$toast.error(err.response?.data?.message || "Impossible de réinitialiser le mot de passe")
      }
    }
  }
}
</script>

<style>
@import '../../../../../../../public/homePage/css/auth.css';
</style>
