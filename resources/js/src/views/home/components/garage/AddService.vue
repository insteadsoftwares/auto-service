<template>
  <div>
    <b-modal id="add-services-modal" title="Ajouter des services" hide-footer backdrop modal-class="custom-backdrop">
		 <validation-observer ref="addServicesValidation" #default="{ handleSubmit }">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- service -->
              <b-form-group class="mb-2">
                <validation-provider
                  #default="{ errors }"
                  name="service"
                  rules="required"
                >
				  <b-form-group label="Service" label-for="service-id">
					<v-select
						v-model="service_ids" 
						:clearable="true" 
						multiple
						:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" 
						:options="filteredServices"
						:reduce="val => val.id" 
						label="name" 
						input-id="service-id">
						<template v-slot:no-options>
							<div class="no-options">
								Désolé, pas d’option de correspondance
							</div>
						</template>
					</v-select>
    			  </b-form-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- submit buttons -->
              <b-button
                type="submit"
                block
                @click="validationForm"
				class="submit-btn"
				style="padding: 10px 24px;"
              >
                Ajouter
              </b-button>
            </b-form>
		</validation-observer>
	</b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { BFormGroup, BFormInput, BForm, BButton, BInputGroup, BInputGroupAppend, BInputGroupPrepend } from 'bootstrap-vue'
import { required } from '@validations'
import store from '@/store'
import vSelect from 'vue-select'

export default {
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
	vSelect
  },
  props: {
    garageServices: {
		type: Array,
	},
  },
  data() {
    return {
	  required,
	  services: [],
	  service_ids: [],
    }
  },
  methods: {
	validationForm() {
		this.$refs.addServicesValidation.validate().then(success => {
			if (success) {
				this.addServices()
			}
		})
	},
	addServices(){
		store.dispatch('garage-service-module/addGarageServices', {'service_ids': this.service_ids}).then((data) => {
			this.$toast.success('Les services on été ajouter avec succès')
			this.$emit('services-added', data)
			this.service_ids = []
			this.$bvModal.hide('add-services-modal')
		}).catch(() => {
			this.$toast.error('An unexpected error occured! Please retry')
		})
	}
  },
  computed: {
	filteredServices() {
		if (!this.garageServices || !this.services.length) {
			return this.services
		}

		const existingServiceIds = this.garageServices.map(gs => gs.service_id)

		return this.services.filter(
			service => !existingServiceIds.includes(service.id)
		)
	}
  },
  created() {
	store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
	})
  },
}
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
