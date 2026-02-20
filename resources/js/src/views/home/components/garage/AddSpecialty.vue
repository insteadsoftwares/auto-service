<template>
  <div>
    <b-modal id="add-specialty-modal" title="Ajouter des spécialités" hide-footer backdrop modal-class="custom-backdrop">
		<validation-observer ref="addSpecialtyValidation" #default="{ handleSubmit }">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- Type Id -->
			<b-form-group label="Type de véhicule" label-for="type-id" class="mb-2">
				<v-select v-model="garageSpecialty.type_ids" :clearable="true" multiple
					:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="vehicleTypes"
					:reduce="val => val.id" label="type" input-id="type-id">
					<template v-slot:no-options>
						<div class="no-options">
							Désolé, pas d’option de correspondance
						</div>
					</template>
				</v-select>
			</b-form-group>

		  <!-- Brand Id -->
			<b-form-group label="Marque de véhicule" label-for="brand-id" class="mb-2">
				<v-select v-model="garageSpecialty.brand_ids" :clearable="true" multiple
					:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="vehicleBrands"
					:reduce="val => val.id" label="name" input-id="brand-id">
					<template v-slot:no-options>
						<div class="no-options">
							Désolé, pas d’option de correspondance
						</div>
					</template>
				</v-select>
			</b-form-group>

		  	<!-- Modele Id -->
			<b-form-group label="Modèle de véhicule" label-for="modele-id">
                <validation-provider #default="{ errors }" name="modèle" rules="required">
					<v-select v-model="garageSpecialty.modele_ids" :clearable="true" multiple
						:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="filteredModeles"
						:reduce="val => val.id" label="modele" input-id="modele-id">
						<template v-slot:no-options>
							<div class="no-options">
								Désolé, pas d’option de correspondance
							</div>
						</template>
					</v-select>
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
	vSelect
  },
  props: {
    garageSpecialties: {
		type: Array,
	},
  },
  data() {
    return {
	  required,
	  vehicleBrands: [],
      vehicleTypes: [],
      vehicleModeles: [],
	  garageSpecialty: {
		type_ids: [],
		brand_ids: [],
		modele_ids: [],
	  }
    }
  },
  methods: {
	validationForm() {
		this.$refs.addSpecialtyValidation.validate().then(success => {
			if (success) {
				this.addSpecialties()
			}
		})
	},
	addSpecialties(){
		store.dispatch('garage-specialty-module/addGarageSpecialties', { 'type_ids': this.garageSpecialty.type_ids, 'brand_ids': this.garageSpecialty.brand_ids ,'modele_ids': this.garageSpecialty.modele_ids }).then((data) => {
			this.$toast.success('Les spécialités on été ajouter avec succès')
			this.$emit('specialties-added', data)
			this.garageSpecialty = { type_ids: [], brand_ids: [], modele_ids: [] }
			this.$bvModal.hide('add-specialty-modal')
		}).catch(() => {
			this.$toast.error('An unexpected error occured! Please retry')
		})
	}
  },
  computed: {
	filteredModeles() {
		const modeles = this.vehicleModeles || []
		const existingModeleIds = this.garageSpecialties.map(
			s => s.vehicle_modele_id
		)

		return modeles.filter(modele => {
			const matchType = !Array.isArray(this.garageSpecialty.type_ids) || !this.garageSpecialty.type_ids.length || this.garageSpecialty.type_ids.includes(modele.type_id)
			const matchBrand = !Array.isArray(this.garageSpecialty.brand_ids) || !this.garageSpecialty.brand_ids.length || this.garageSpecialty.brand_ids.includes(modele.brand_id)
			const notExist = !existingModeleIds.includes(modele.id)
			return matchType && matchBrand && notExist
		})
	},
  },
  created() {
	store.dispatch('vehicle-brand-module/getVehicleBrands', { active: 1 }).then(() => {
		this.vehicleBrands = store.getters['vehicle-brand-module/vehicle_brands'].nodes
	})

	store.dispatch('vehicle-type-module/getVehicleTypes').then(() => {
		this.vehicleTypes = store.getters['vehicle-type-module/vehicle_types'].nodes
	})
	
	store.dispatch('vehicle-modele-module/getVehicleModeles', { active: 1 }).then(() => {
		this.vehicleModeles = store.getters['vehicle-modele-module/vehicle_modeles'].nodes
	})
  },
}
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
