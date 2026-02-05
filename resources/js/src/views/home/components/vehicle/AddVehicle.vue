<template>
  <div id="add-vehicle-modal">
	  <h3>{{ title }}</h3>
      <validation-observer ref="observer" v-slot="{ handleSubmit }">
        <b-form class="auth-login-form mt-2" @submit.prevent="handleSubmit(onSubmit)">
			<b-row>
			  <b-col cols="12" md="6">
				<!-- Matricule -->
				<b-form-group label="Matricule de la voiture">
					<b-form-input v-model="vehicle.registration_number"/>
				</b-form-group>
			  </b-col>

			  <b-col cols="12" md="6">
				<!-- Année -->
				<validation-provider
					name="année du véhicule"
					:rules="yearRules"
					v-slot="{ errors }"
				>
					<b-form-group label="Année du véhicule">
						<b-form-input
							v-model="vehicle.year"
							type="number"
							:state="errors.length ? false : null"
						/>
						<b-form-invalid-feedback>
							{{ errors[0] }}
						</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>
			  </b-col>
			  
			  <b-col cols="12" md="6">
				<!-- Type -->
				<validation-provider
					name="type de véhicule"
					rules="required"
					v-slot="{ errors }"
				>
					<b-form-group label="Type de véhicule">
					<v-select
						v-model="vehicle.type_id"
						:options="vehicleTypes"
						:reduce="val => val.id"
						label="type"
						:class="{ 'is-invalid': errors.length }"
						@input="onTypeOrBrandChange"
					/>
					<div class="invalid-feedback d-block" v-if="errors.length">
						{{ errors[0] }}
					</div>
					</b-form-group>
				</validation-provider>
			  </b-col>
			  
			  <b-col cols="12" md="6">
				<!-- Marque -->
				<b-form-group label="Marque de véhicule">
					<v-select
						v-model="vehicle.brand_id"
						:options="vehicleBrands"
						:reduce="val => val.id"
						label="name"
						@input="onTypeOrBrandChange"
					/>
				</b-form-group>
			  </b-col>
			  
			  <b-col cols="12" md="6">
				<!-- Modèle -->
				<b-form-group label="Modèle de véhicule">
					<v-select
						v-model="vehicle.modele_id"
						:options="filteredModeles"
						:reduce="val => val.id"
						label="modele"
					/>
				</b-form-group>
			  </b-col>
			  <b-col cols="12">
				<!-- Description  -->
				<b-form-group label="Description ">
					<b-form-textarea v-model="vehicle.description"/>
				</b-form-group>
			  </b-col>
			</b-row>
          <!-- Bouton -->
          <b-button type="submit" block class="submit-btn" style="border-radius: 8px !important;">
            {{ submitText }}
          </b-button>

        </b-form>
      </validation-observer>
  </div>
</template>
<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { numeric, min_value, max_value } from 'vee-validate/dist/rules'
import { BForm, BFormGroup, BFormInput, BButton, BFormInvalidFeedback, BRow, BCol, BFormTextarea } from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    BForm,
    BFormGroup,
    BFormInput,
    BButton,
    BFormInvalidFeedback,
    vSelect,
	BRow, 
	BCol,
	BFormTextarea
  },
  props: {
	vehicleToEdit: {
		type: Object,
		default: null,
	},
  },
  data() {
    return {
	  numeric,
	  required,
	  min_value,
	  max_value,
      vehicle: this.getEmptyVehicle(),
      vehicleTypes: [],
      vehicleBrands: [],
      vehicleModeles: [],
    }
  },
  watch: {
	vehicleToEdit: {
		immediate: true,
		handler(vehicle) {
			if (vehicle) {
				this.vehicle = {
					id: vehicle.id,
					registration_number: vehicle.registration_number,
					year: vehicle.year,
					type_id: vehicle.vehicle_type.id,
					brand_id: vehicle.vehicle_brand ? vehicle.vehicle_brand.id : null,
					modele_id: vehicle.vehicle_modele ? vehicle.vehicle_modele.id : null,
					description: vehicle.description,
				}
			} else {
				this.vehicle = this.getEmptyVehicle()
			}
		},
	},
  },
  computed: {
	isEdit() {
		return !!this.vehicleToEdit
	},
	title() {
		return this.isEdit ? 'Modifier le véhicule' : 'Ajouter véhicule'
	},
	submitText() {
		return this.isEdit ? 'Modifier' : 'Ajouter'
	},
    filteredModeles() {
      return this.vehicleModeles.filter(m =>
        (!this.vehicle.type_id || m.type_id === this.vehicle.type_id) &&
        (!this.vehicle.brand_id || m.brand_id === this.vehicle.brand_id)
      )
    },
	yearRules() {
		return `numeric|min_value:1900|max_value:${new Date().getFullYear()}`
	},
  },
  methods: {
	getEmptyVehicle() {
		return {
			registration_number: '',
			year: '',
			type_id: null,
			brand_id: null,
			modele_id: null,
			description: ''
		}
	},
    async onSubmit() {
		try {
			if (this.isEdit) {
				await this.$store.dispatch('client-vehicle-module/editClientVehicle', this.vehicle)
			} else {
				await this.$store.dispatch('client-vehicle-module/addClientVehicle', this.vehicle)
			}

			this.$bvModal.hide('add-vehicle-modal')
			this.$emit('vehicle-added')
			this.resetForm()
			this.$toast.success('Rendez-vous réservé avec succès !')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
	},
	resetForm() {
		this.vehicle = this.getEmptyVehicle()

		this.$nextTick(() => {
			this.$refs.observer.reset()
		})
	},
	onModalHidden() {
		this.resetForm()
		this.$emit('reset-edit')
	},
	onTypeOrBrandChange() {
		if (this.vehicle.modele_id) {
			const isValid = this.filteredModeles.some(
				modele => modele.id === this.vehicle.modele_id
			)
			if (!isValid) {
				this.vehicle.modele_id = null
			}
		}
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
<style>
@import '../../../../../../../public/homePage/css/auth.css';

/* .v-select .vs__dropdown-toggle{
	height: 48px !important;
	border-radius: 0 !important;
	background: #f3f7fa !important;
} */
</style>