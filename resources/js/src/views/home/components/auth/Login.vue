<template>
  <div >
    <!-- Login Modal -->
    <b-modal id="login-modal" title="Connexion" hide-footer backdrop modal-class="custom-backdrop" @shown="renderGoogleButton">
		
		<!-- form -->
          <validation-observer ref="loginValidation" #default="{ handleSubmit }">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- email -->
              <b-form-group class="mb-2">
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
						v-model="userEmail"
						:state="errors.length > 0 ? false:null"
						name="login-email"
						placeholder="Email"
					/>
    			  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- forgot password -->
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="mot de passe"
                  rules="required"
                >
                  <b-input-group
                    class="custom-input"
                    :class="errors.length > 0 ? 'is-invalid':null"
                  >
				  	<b-input-group-prepend is-text>
						<feather-icon icon="LockIcon" />
					</b-input-group-prepend>
                    <b-form-input
                      id="login-password"
                      v-model="password"
                      :state="errors.length > 0 ? false:null"
                      class="form-control-merge"
                      :type="passwordFieldType"
                      name="login-password"
                      placeholder="Mot de passe"
                    />
                    <b-input-group-append is-text>
                      <feather-icon
                        class="cursor-pointer"
                        :icon="passwordToggleIcon"
                        @click="togglePasswordVisibility"
                      />
                    </b-input-group-append>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
			  <div class="mb-2 forgot-password">
				<b-button variant="link" size="sm" @click="openForgotPasswordModal">
					Mot de passe oublié ?
				</b-button>
			  </div>

              <!-- submit buttons -->
              <b-button
                type="submit"
                block
                @click="validationForm"
				class="submit-btn"
              >
                Se connecter
              </b-button>

			  <div id="googleLoginButton"></div>
            </b-form>
		</validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { BFormGroup, BFormInput, BInputGroupAppend, BInputGroup, BForm, BButton, BInputGroupPrepend } from 'bootstrap-vue'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store/index'

export default {
  components: {
    BFormGroup,
    BFormInput,
    BInputGroupAppend,
    BInputGroup,
    BForm,
    BButton,
    BInputGroupPrepend,
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      password: '',
      userEmail: '',
      required,
      email,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  mounted() {
	window.handleGoogleLogin = this.handleGoogleResponse;
  },
  methods: {
	validationForm() {
		this.$refs.loginValidation.validate().then(success => {
			if (success) {
				this.onSubmit()
			}
		})
	},
	async onSubmit() {
		try {
			const loginData = {
				email: this.userEmail,
				password: this.password,
			}

			const response = await this.$store.dispatch('auth-module/login', loginData)

			if (response.user.role.name === 'admin') {
				this.$router.push({ name: 'AdminDashboard' })
			} 

			this.$bvModal.hide('login-modal')
			this.$toast.success('Connexion réussie')

		} catch (error) {
			this.$toast.error(error.response?.data?.message || 'Identifiants incorrects')
		}
	},
	renderGoogleButton() {
      if (window.google && window.google.accounts) {
        google.accounts.id.initialize({
          client_id: "576641158920-6uiaotahalebruethc5jio96q32nb9aj.apps.googleusercontent.com",
          callback: this.handleGoogleResponse,
        });

        google.accounts.id.renderButton(
          document.getElementById("googleLoginButton"),
          { theme: "outline", size: "large", type: "standard" }
        );
      }
    },
	async handleGoogleResponse(resp) {
		const token = resp.credential;
		const payload = JSON.parse(atob(token.split(".")[1]));

		const userData = {
			email: payload.email,
			google_id: payload.sub,
			first_name: payload.given_name,
			last_name: payload.family_name,
		};

		try {
			const response = await this.$store.dispatch("auth-module/loginWithGoogle", userData);
			this.$bvModal.hide("login-modal");
			this.$toast.success('Connexion réussie via Google')
		} catch (err) {
			this.$toast.error(err.response?.data?.message || "Impossible de se connecter")
		}
	},
	openForgotPasswordModal() {
		console.log('openForgotPasswordModal')
		this.$bvModal.show('forgot-password-modal');
	}
  }
}
</script>

<style>
@import '../../../../../../../public/homePage/css/auth.css';
#googleLoginButton #button-label {
  display: none !important;
}

.forgot-password{
	text-align: right;
}

.forgot-password .btn{
	color: #9ea5b1;
    text-decoration: none;
	font-size: 12px;
}

</style>
