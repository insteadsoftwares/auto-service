<template>
  <div>
    <b-modal
      id="register-modal"
      title="Inscription"
      hide-footer
      backdrop
      modal-class="custom-backdrop"
      @shown="renderGoogleButton"
	  @hidden="resetForm"
    >
	  <!-- form -->
          <validation-observer ref="loginValidation" #default="{ handleSubmit }">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- first name -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="nom"
                  rules="required"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="UserIcon" />
					</b-input-group-prepend>

					<b-form-input
						id="login-first-name"
						v-model="userFirstName"
						:state="errors.length > 0 ? false:null"
						name="login-first-name"
						placeholder="Nom"
					/>
				  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              
			  <!-- last name -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="prénom"
                  rules="required"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="UserCheckIcon" />
					</b-input-group-prepend>
					<b-form-input
						id="login-last-name"
						v-model="userLastName"
						:state="errors.length > 0 ? false:null"
						name="login-last-name"
						placeholder="Prénom"
					/>
				  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              
			  <!-- address -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="adresse"
                  rules="required"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="MapPinIcon" />
					</b-input-group-prepend>
					<b-form-input
						id="login-address"
						v-model="userAddress"
						:state="errors.length > 0 ? false:null"
						name="login-address"
						placeholder="Adresse"
					/>
				  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
			  
			  <!-- phone number -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="numéro de téléphone"
                  rules="required|phone"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="PhoneIcon" />
					</b-input-group-prepend>
					<b-form-input
						id="login-phone-number"
						v-model="userPhoneNumber"
						:state="errors.length > 0 ? false:null"
						name="login-phone-number"
						placeholder="Numéro de téléphone"
					/>
				  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
			  
              <!-- email -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required|email"
                >
				  <b-input-group class="custom-input">
					<b-input-group-prepend is-text>
						<feather-icon icon="MailIcon" />
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

              <!-- password -->
              <b-form-group class="mb-2">
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
                      placeholder="mot de passe"
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

              <!-- submit buttons -->
              <b-button
                type="submit"
                block
                @click="validationForm"
				class="submit-btn"
              >
                Inscription
              </b-button>
			  
			  <!-- Bouton Google -->
			  <div id="googleButton"></div>
            </b-form>
		</validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { BFormGroup, BFormInput, BInputGroupAppend, BInputGroup, BForm, BButton, BInputGroupPrepend } from 'bootstrap-vue'
import { required, email, phone } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store/index'

export default {
  name: "Register",
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
	  userFirstName: '',
      userLastName: '',
      userAddress: '',
      userPhoneNumber: '',
      userEmail: '',
      password: '',
      required,
      email,
      phone,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  mounted() {
    window.handleGoogleResponse = (resp) => {
      const token = resp.credential;
      const payload = JSON.parse(atob(token.split(".")[1]));

	  const nameParts = (payload.name || "").split(" ");
      this.userLastName = nameParts[0] || "";
      this.userFirstName = nameParts.slice(1).join(" ") || "";
      this.userEmail = payload.email || "";
    };
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
			const registerData = {
				role: 'client',
				first_name: this.userFirstName,
				last_name: this.userLastName,
				address: this.userAddress,
				phone_number: this.userPhoneNumber,
				email: this.userEmail,
				password: this.password
			}

			const response = await this.$store.dispatch('auth-module/register', registerData)

			this.$bvModal.hide('register-modal')
			this.resetForm()
			this.$toast.success('Inscription réussie')
		} catch (error) {
			console.error(error);
			
			this.$toast.error(error.response.data.errors ? Object.values(error.response.data.errors).join('<br>') : "Une erreur est survenue.")
		}
	},
	renderGoogleButton() {
		if (window.google && window.google.accounts) {
			google.accounts.id.initialize({
				// client_id: "196743290162-sh95lcm615c1hmu17nhbhp4a9bbi114g.apps.googleusercontent.com",
				client_id: "576641158920-6uiaotahalebruethc5jio96q32nb9aj.apps.googleusercontent.com",
				callback: async (resp) => {
					const token = resp.credential;
					const payload = JSON.parse(atob(token.split(".")[1]));

					const userData = {
						role: 'client',
						first_name: payload.given_name || '',
						last_name: payload.family_name || '',
						email: payload.email,
						google_id: payload.sub,
						address: 'tunis',
						phone_number: '99999999',
					};

					try {
						await this.$store.dispatch('auth-module/registerWithGoogle', userData);
						this.$bvModal.hide('register-modal');
						this.$toast.success('Inscription réussie via Google')
					} catch (err) {
						console.error(err);
						this.$toast.error('Impossible de se connecter via Google')
					}
				}
			});

			google.accounts.id.renderButton(
				document.getElementById("googleButton"),
				{ theme: "outline", size: "large", type: "standard" }
			);
		}
	},
	resetForm(){
		this.userFirstName = ''
		this.userLastName = ''
		this.userAddress = ''
		this.userPhoneNumber = ''
		this.userEmail = ''
		this.password = ''
	}
  }
}
</script>

<style>
@import '../../../../../../../public/homePage/css/auth.css';
#googleButton #button-label {
  display: none !important;
}
</style>