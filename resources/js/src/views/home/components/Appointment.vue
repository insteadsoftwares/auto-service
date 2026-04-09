<template>
	<div id="appointment-form">
      	<h4 class="text-white">Prendre RDV</h4>
		<div v-if="!showForm">
			<p class="text-white pt-3">Merci de choisir votre véhicule</p>
			<b-row class="text-center">
				<b-col cols="6">
					<div class="vehicle-card" @click="selectVehicle('voiture')">
						<img src="/homePage/img/icon/icon-car.png" alt="Voiture" />
						<p class="mt-2">Voiture</p>
						<small>4 roues</small>
					</div>
				</b-col>

				<b-col cols="6">
					<div class="vehicle-card" @click="selectVehicle('moto')">
						<img src="/homePage/img/icon/icon-motocycle.png" alt="Voiture" />
						<p class="mt-2">Moto</p>
						<small>2 roues</small>
					</div>
				</b-col>
			</b-row>
		</div>
		<div v-else>
			<b-button variant="link" class="back-link mt-3" @click="showForm = false">
				← Retour au choix du véhicule
			</b-button>
			<validation-observer ref="observer" v-slot="{ handleSubmit }">
				<b-form class="auth-login-form mt-2" @submit.prevent="handleSubmit(onSubmit)">
					<!-- Guest name -->
					<validation-provider name="nom" rules="required" v-slot="{ errors }" v-if="!isLoggedIn">
						<b-form-group label="Nom" label-class="mt-2">
							<b-form-input
								v-model="guest.name"
								:class="{ 'is-invalid': errors.length }"
								class="m-0"
								placeholder="Votre nom"
							/>
							<div class="invalid-feedback d-block" v-if="errors.length">
								{{ errors[0] }}
							</div>
						</b-form-group>
					</validation-provider>

					<!-- phone -->
					<validation-provider 
						name="numéro de téléphone" 
						rules="required|phone" 
						v-slot="{ errors }"
						v-if="!isLoggedIn"
					>
						<b-form-group label="Numéro de téléphone" label-class="mt-2">
							<b-form-input
								v-model="guest.phone"
								:class="{ 'is-invalid': errors.length }"
								class="m-0"
								placeholder="Votre numéro de téléphone"
							/>
							<div class="invalid-feedback d-block" v-if="errors.length">
								{{ errors[0] }}
							</div>
						</b-form-group>
					</validation-provider>

					<b-form-group label="Véhicule" v-if="isLoggedIn">
						<v-select
							v-model="vehicle_id"
							:options="vehicles"
							:reduce="val => val.id"
							:get-option-label="vehicleLabel"
							placeholder="Sélectionner véhicule"
						/>
					</b-form-group>
				
					<!-- Marque -->
					<b-form-group label="Marque de véhicule" label-class="mt-2">
						<v-select
							v-model="vehicle.brand_id"
							:options="vehicleBrands"
							:reduce="val => val.id"
							label="name"
							@input="onTypeOrBrandChange"
							placeholder="Sélectionner marque de véhicule"
						/>
					</b-form-group>
				
					<!-- Modèle -->
					<b-form-group label="Modèle de véhicule" label-class="mt-2">
						<v-select
							v-model="vehicle.modele_id"
							:options="filteredModeles"
							:reduce="val => val.id"
							label="modele"
							placeholder="Sélectionner modèle de véhicule"
						/>
					</b-form-group>

					<b-button type="submit" block class="submit-btn">Réserver</b-button>

				</b-form>
			</validation-observer>
		</div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, phone } from '@validations'
import { BForm, BFormGroup, BFormInput, BButton, BFormInvalidFeedback, BRow, BCol } from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'
import { mapState } from 'vuex'

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
	  phone, 
      vehicleTypes: [],
      vehicleBrands: [],
      vehicleModeles: [],
	  vehicle:{
		type_id: null,
		brand_id: null,
		modele_id: null,
	  },
	  guest:{
		name: null,
		phone: null,
	  },
	  showForm: false,
	  vehicle_id: null,
      vehicles: [],
    }
  },
  mounted() {
    this.$store.dispatch('auth-module/checkUser')
  },
  computed: {
    ...mapState('auth-module', {
      isLoggedIn: state => !!state.currentUser,
    }),
    filteredModeles() {
      return this.vehicleModeles.filter(m =>
        (!this.vehicle.type_id || m.type_id === this.vehicle.type_id) &&
        (!this.vehicle.brand_id || m.brand_id === this.vehicle.brand_id)
      )
    },
  },
  watch: {
	vehicle_id(newVal) {
		if (newVal) {
			this.vehicle.brand_id = null
			this.vehicle.modele_id = null
		}
	},
	'vehicle.brand_id'(newVal) {
		if (newVal) {
			this.vehicle_id = null
		}
	},
	'vehicle.modele_id'(newVal) {
		if (newVal) {
			this.vehicle_id = null
		}
	}
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
		if(this.isLoggedIn){
			if (!this.vehicle_id && !this.vehicle.brand_id) {
				this.$toast.error('Veuillez choisir un véhicule ou une marque')
				return
			}

			if (this.vehicle_id && this.vehicle.brand_id) {
				this.$toast.error('Veuillez choisir soit un véhicule soit une marque')
				return
			}

			if(!this.vehicle_id){
				store.dispatch('client-vehicle-module/clineAppointmentFromHomePage', { type_id: this.vehicle.type_id, brand_id: this.vehicle.brand_id, 
					modele_id: this.vehicle.modele_id }).then(() => {
					const result = store.getters['client-vehicle-module/appointmentFromHomePage']
					this.$router.push({path: '/addAppointment', query: { vehicle_id: result.clientVehicle.id }});
				})
			}
			else{
				this.$router.push({path: '/addAppointment', query: { vehicle_id: this.vehicle_id }});
			}
		}
		else{
			if (!this.guest.name || !this.guest.phone) {
				this.$toast.error('Veuillez remplir vos informations')
				return
			}

			if (!this.vehicle.brand_id) {
				this.$toast.error('Veuillez sélectionner une marque')
				return
			}

			this.$router.push({path: '/addAppointment', query: { type_id: this.vehicle.type_id, brand_id: this.vehicle.brand_id, modele_id: this.vehicle.modele_id, 
				guest_name: this.guest.name, guest_phone: this.guest.phone }});
		}
	},
	selectVehicle(type) {
		this.vehicle_id = null
		this.guest.name = null
		this.guest.phone = null
		this.vehicle.type_id = null
		this.vehicle.brand_id = null
		this.vehicle.modele_id = null

		const foundType = this.vehicleTypes.find(t => t.type.toLowerCase() === type.toLowerCase())

		if (foundType) {
			this.vehicle.type_id = foundType.id
			this.showForm = true
			this.onTypeOrBrandChange()

			if(this.isLoggedIn){
				this.$store.dispatch('client-vehicle-module/getClientVehicles').then(() => {
					const allVehicles = store.getters['client-vehicle-module/clientVehicles']

					this.vehicles = allVehicles.filter(
						v => v.type_id === this.vehicle.type_id
					)
				})
			}
		}
	},
	vehicleLabel(vehicle) {
		const brand = vehicle.vehicle_brand ? vehicle.vehicle_brand.name : '-'
		const modele = vehicle.vehicle_modele ? vehicle.vehicle_modele.modele : '-'
		return `${vehicle.vehicle_type.type} | ${brand} | ${modele}`
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

#appointment-form .vs__no-options{
    color: black !important;
}

.vehicle-card {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 20px;
  cursor: pointer;
  transition: 0.3s;
  border: 2px solid transparent;
}

.vehicle-card:hover {
  transform: translateY(-5px);
  border-color: #ff6b3d;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.vehicle-card img {
  width: 60px;
  height: auto;
}

.vehicle-card p {
  font-weight: bold;
  margin: 0;
  color: #002c42;
}

.vehicle-card small {
  color: gray;
}

.back-link {
  color: #ffffff;
  font-weight: 500;
  text-decoration: none;
  padding: 0;
}

.back-link:hover {
  text-decoration: underline;
  color: #ffd6c9;
}
</style>
