<template>
	<div id="appointment-form">
      	<h4 class="text-white">Prendre RDV</h4>
	  	<validation-observer ref="observer" v-slot="{ handleSubmit }">
        	<b-form class="auth-login-form mt-2" @submit.prevent="handleSubmit(onSubmit)">
				<!-- service -->
				<validation-provider
					name="service"
					rules="required"
					v-slot="{ errors }"
				>
					<b-form-group label="Service" label-class="mt-2">
						<v-select
							v-model="vehicle.service_id"
							:options="services"
							:reduce="val => val.id"
							label="name"
							:class="{ 'is-invalid': errors.length }"
						/>
						<div class="invalid-feedback d-block" v-if="errors.length">
							{{ errors[0] }}
						</div>
					</b-form-group>
				</validation-provider>

				<!-- Type -->
				<validation-provider
					name="type de véhicule"
					rules="required"
					v-slot="{ errors }"
				>
					<b-form-group label="Type de véhicule" label-class="mt-2">
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
			  
				<!-- Marque -->
				<validation-provider
					name="marque de véhicule"
					rules="required"
					v-slot="{ errors }"
				>
					<b-form-group label="Marque de véhicule" label-class="mt-2">
						<v-select
							v-model="vehicle.brand_id"
							:options="vehicleBrands"
							:reduce="val => val.id"
							label="name"
							:class="{ 'is-invalid': errors.length }"
							@input="onTypeOrBrandChange"
						/>
						<div class="invalid-feedback d-block" v-if="errors.length">
							{{ errors[0] }}
						</div>
					</b-form-group>
				</validation-provider>
			  
				<!-- Modèle -->
				<validation-provider
					name="modèle de véhicule"
					rules="required"
					v-slot="{ errors }"
				>
					<b-form-group label="Modèle de véhicule" label-class="mt-2">
						<v-select
							v-model="vehicle.modele_id"
							:options="filteredModeles"
							:reduce="val => val.id"
							label="modele"
							:class="{ 'is-invalid': errors.length }"
						/>
						<div class="invalid-feedback d-block" v-if="errors.length">
							{{ errors[0] }}
						</div>
					</b-form-group>
				</validation-provider>

				<b-button type="submit" block class="submit-btn">
					Envoyer
				</b-button>

        	</b-form>
      	</validation-observer>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { BForm, BFormGroup, BFormInput, BButton, BFormInvalidFeedback, BRow, BCol } from 'bootstrap-vue'
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
	BCol
  },
  data() {
    return {
	  required,
      vehicleTypes: [],
      vehicleBrands: [],
      vehicleModeles: [],
      services: [],
	  vehicle:{
		type_id: null,
		brand_id: null,
		modele_id: null,
		service_id: null,
	  }
    }
  },
  computed: {
    filteredModeles() {
      return this.vehicleModeles.filter(m =>
        (!this.vehicle.type_id || m.type_id === this.vehicle.type_id) &&
        (!this.vehicle.brand_id || m.brand_id === this.vehicle.brand_id)
      )
    },
  },
  methods: {
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
	onSubmit(){

	}
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

    store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
	})
  },
}
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
<style >
#appointment-form i, #appointment-form span, #appointment-form a {
    display: block;
}

#appointment-form .vs__dropdown-toggle {
    background: white;
}

#appointment-form .submit-btn{
	background: white;
    color: #F35C27 !important;
}
</style>
