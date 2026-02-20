<template>
  	<div id="add-appointment">
		<h3>Prendre Rendez-vous</h3>
		<validation-observer ref="observer" v-slot="{ handleSubmit }">	
			<b-form action="index.html"  @submit.prevent="handleSubmit(onSubmit)">
				<b-row>
					<b-col class="col-lg-6 col-md-6 col-12" v-if="isLoggedIn">
						<validation-provider
							name="véhicule"
							rules="required"
							v-slot="{ errors }"
						>
							<b-form-group label="Véhicule">
								<v-select
									v-model="appointment.vehicle_id"
									:options="vehicles"
									:reduce="val => val.id"
									:get-option-label="vehicleLabel"
									:class="{ 'is-invalid': errors.length }"
									placeholder="Sélectionner véhicule"
								/>
								<div class="invalid-feedback d-block" v-if="errors.length">
									{{ errors[0] }}
								</div>
							</b-form-group>
						</validation-provider>
					</b-col>
					<b-col class="col-lg-6 col-md-6 col-12" v-else>
						<b-form-group label="Véhicule">
							<b-form-input :value="guestVehicleLabel" disabled />
						</b-form-group>
					</b-col>
					<b-col class="col-lg-6 col-md-6 col-12">
						<validation-provider
							name="service"
							rules="required"
							v-slot="{ errors }"
						>
							<b-form-group label="Service">
								<v-select
									v-model="appointment.service_id"
									:options="services"
									:reduce="val => val.id"
									label="name"
									:class="{ 'is-invalid': errors.length }"
									placeholder="Sélectionner service"
									@input="onServiceChange"
								/>
								<div class="invalid-feedback d-block" v-if="errors.length">
									{{ errors[0] }}
								</div>
							</b-form-group>
						</validation-provider>
					</b-col>
					<b-col class="col-lg-6 col-md-6 col-12" v-if="!isLoggedIn">
						<validation-provider name="nom" rules="required" v-slot="{ errors }">
							<b-form-group label="Nom">
								<b-form-input
									v-model="appointment.guest_name"
									:class="{ 'is-invalid': errors.length }"
									placeholder="Votre nom"
								/>
								<div class="invalid-feedback d-block" v-if="errors.length">
									{{ errors[0] }}
								</div>
							</b-form-group>
						</validation-provider>
					</b-col>

					<b-col class="col-lg-6 col-md-6 col-12" v-if="!isLoggedIn">
						<validation-provider 
							name="numéro de téléphone" 
							rules="required|numeric|min:8" 
							v-slot="{ errors }"
						>
							<b-form-group label="Numéro de téléphone">
								<b-form-input
									v-model="appointment.guest_phone"
									:class="{ 'is-invalid': errors.length }"
									placeholder="Votre numéro"
								/>
							<div class="invalid-feedback d-block" v-if="errors.length">
								{{ errors[0] }}
							</div>
							</b-form-group>
						</validation-provider>
					</b-col>
					<b-col class="col-12 text-center" v-if="!showGarages">
						<button class="main-btn mt-30 btn-submit" style="border: none;">Confirmer</button>
					</b-col>
				</b-row>
			</b-form>
		</validation-observer>

		<div v-if="showGarages">
			<b-row class="mt-4">
				<b-col cols="12" md="4">
					<div class="garage-list">
						<ul class="list-unstyled">
							<li
								v-for="garage in garagesByService"
								:key="garage.id"
								:class="{ 'selected-garage': selectedGarage && selectedGarage.id === garage.id }"
								@click="selectGarage(garage)"
								style="cursor:pointer; padding: 10px; border-bottom: 1px solid #ddd; width: 100%;"
							>
								<b>{{ garage.name }}</b><br>
								{{ garage.address }}
							</li>
						</ul>
					</div>
				</b-col>

				<b-col cols="12" md="8">
					<div id="map" style="height: 425px; width: 100%;"></div>
				</b-col>
			</b-row>
		</div>
		
		<div v-if="showDate">
			<b-row class="mt-5">
				<b-col class="col-lg-6 col-md-6 col-12">
					<b-form-group label="Date du rendez-vous">
						<flat-pickr v-model="appointment.appointment_date" 
							placeholder="Sélectionner date" 
							:config="dateConfig" 
							class="form-control flat-pickr" 
							@on-change="updateDisabledTimes"
						/>
					</b-form-group>
				</b-col>
				<b-col class="col-lg-6 col-md-6 col-12">
					<b-form-group label="Heure du rendez-vous">
						<v-select
							v-model="appointment.appointment_time"
							:options="hours"
							placeholder="Sélectionner l'heure"
							:clearable="false"
						/>
					</b-form-group>
				</b-col>
			</b-row>

			<b-button block class="submit-btn btn-submit" @click="reserveAppointment">
				Réserver Rendez-vous
			</b-button>
		</div>
  	</div>
</template>

<script>
import { BFormInput, BFormGroup, BButton, BFormInvalidFeedback, BRow, BForm, BCol } from 'bootstrap-vue'
import store from '@/store'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
// import { required } from '@validations'
import { required, numeric, min } from 'vee-validate/dist/rules'
import vSelect from 'vue-select'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import { mapState } from 'vuex'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
	BFormInvalidFeedback,
    BFormInput,
    BFormGroup,
    BButton,
    BRow,
    BCol,
    BForm,
	vSelect,
	FlatPickr
  },
  data() {
    return {
      showGarages: false,
	  showDate: false,
	  selectedGarage: null,
      upcomingGarageAppointments: [],
      services: [],
	  appointment: {
		service_id: null,
		vehicle_id: null,
		garage_id: null,
		appointment_date: null,
      	appointment_time: null,
		guest_name: null,
  		guest_phone: null,
	  },
	  required,
	  garagesByService: [],
      vehicles: [],
	  map: null,
	  markers: [],
	  dateConfig: {
        dateFormat: 'Y-m-d',
        minDate: new Date(),
		altInput: true, 
		altFormat: 'd/m/Y',
      },
	  hours: ["08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00"],
	  guestVehicle: null
    }
  },
  computed: {
    ...mapState('auth-module', {
      isLoggedIn: state => !!state.currentUser,
    }),
	yearRules() {
		return `numeric|min_value:1900|max_value:${new Date().getFullYear()}`
	},
	guestVehicleLabel() {
		if (!this.guestVehicle) return ''

		const brand = this.guestVehicle.vehicle_brand?.name || '-'
		const modele = this.guestVehicle.vehicle_modele?.modele || '-'
		const type = this.guestVehicle.vehicle_type?.type || '-'

		return `${type} | ${brand} | ${modele}`
	}
  },
  methods: {
	onSubmit(){
		store.dispatch('garage-module/getGaragesByService', this.appointment.service_id ).then(() => {
			this.garagesByService = store.getters['garage-module/garageByService']
			this.showGarages = true
			this.$nextTick(() => {
				this.initMap()
				this.showGaragesOnMap()
			})
		})
	},
	initMap() {
		const mapEl = document.getElementById('map')
		if (!mapEl) {
			console.error('Map element not found')
			return
		}

		this.map = new google.maps.Map(mapEl, {
			center: { lat: 36.8065, lng: 10.1815 },
			zoom: 7,
		})
	},
	showGaragesOnMap() {
		this.markers.forEach(marker => marker.setMap(null))
		this.markers = []

		const bounds = new google.maps.LatLngBounds()

		this.garagesByService.forEach(garage => {
			if (garage.latitude && garage.longitude) {
			const position = {
				lat: Number(garage.latitude),
				lng: Number(garage.longitude),
			}

			const marker = new google.maps.Marker({
				position,
				map: this.map,
				title: garage.name,
			})

			const infoWindow = new google.maps.InfoWindow({
				content: `
				<strong>${garage.name}</strong><br/>
				${garage.address}
				`,
			})

			marker.addListener("click", () => {
				infoWindow.open(this.map, marker)
			})

			this.markers.push({ marker, garage })
			bounds.extend(position)
			}
		})

		if (this.markers.length) {
			this.map.fitBounds(bounds)
		}
	},
	focusGarage(garage) {
		const found = this.markers.find(m => m.garage.id === garage.id)
		if (!found) return

		this.map.setZoom(15)
		this.map.panTo({
			lat: Number(garage.latitude),
			lng: Number(garage.longitude),
		})

		new google.maps.InfoWindow({
			content: `
			<strong>${garage.name}</strong><br/>
			${garage.address}
			`,
		}).open(this.map, found.marker)
	},
	geocodeAddress(garage) {
		const geocoder = new google.maps.Geocoder()

		geocoder.geocode({ address: garage.address }, (results, status) => {
			if (status === 'OK') {
			const location = results[0].geometry.location

			// Centre la map
			this.map.setZoom(15)
			this.map.setCenter(location)

			// Marker
			const marker = new google.maps.Marker({
				map: this.map,
				position: location,
				title: garage.name,
			})

			new google.maps.InfoWindow({
				content: `
				<strong>${garage.name}</strong><br/>
				${garage.address}
				`,
			}).open(this.map, marker)
			} else {
				console.error('Geocode failed:', status)
			}
		})
	},
	vehicleLabel(vehicle) {
		const brand = vehicle.vehicle_brand ? vehicle.vehicle_brand.name : '-'
		const modele = vehicle.vehicle_brand ? vehicle.vehicle_modele.modele : '-'
		return `${vehicle.vehicle_type.type} | ${brand} | ${modele}`
	},
	selectGarage(garage) {
		this.selectedGarage = garage
		this.appointment.garage_id = garage.id

		if (garage.latitude && garage.longitude) {
			this.focusGarage(garage)
		} else {
			this.geocodeAddress(garage)
		}

		this.$store.dispatch('appointment-module/getUpcomingGarageAppointments', garage.id).then(() => {
			this.upcomingGarageAppointments =
			store.getters['appointment-module/upcomingGarageAppointments']
			this.showDate = true
		})
	},
	reserveAppointment() {
		console.log('reserveAppointment')
		if (!this.appointment.appointment_date || !this.appointment.appointment_time) {
			this.$toast.error('Veuillez sélectionner la date et l’heure du rendez-vous')
			return
		}
		
  		const user = JSON.parse(localStorage.getItem('currentUser'))
		const payload = {
			service_id: this.appointment.service_id,
			vehicle_id: this.appointment.vehicle_id,
			garage_id: this.appointment.garage_id,
			appointment_date: this.appointment.appointment_date,
			appointment_time: this.appointment.appointment_time,
		}

		if (this.isLoggedIn && user) {
			payload.client_id = user.id
			payload.is_client = true
		} else {
			payload.guest_name = this.appointment.guest_name
			payload.guest_phone = this.appointment.guest_phone
			payload.guest_vehicle_type_id = Number(this.guestVehicle.type_id)
			payload.guest_vehicle_brand_id = Number(this.guestVehicle.brand_id)
			payload.guest_vehicle_model_id = Number(this.guestVehicle.modele_id)
			payload.is_client = false
		}

		console.log('payload')
		console.log(payload)
		this.$store.dispatch('appointment-module/addAppointment', payload)
		.then(() => {
			this.$toast.success('Rendez-vous réservé avec succès')
			if (payload.is_client) {
				this.$emit('appointment-added')
			} else {
				this.$router.push({ name: 'HomePage' })
			}
		})
		.catch(err => {
			console.error(err)
			this.$toast.error('Erreur lors de la réservation')
		})
	},
	updateDisabledTimes() {
		this.hours = ["08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00"];

		const selectedDate = this.appointment.appointment_date;
		const today = new Date();
		const todayStr = today.toISOString().slice(0, 10);
		const currentHour = today.getHours();
		const currentMinute = today.getMinutes();

		if (selectedDate === todayStr) {
			this.hours = this.hours.filter(hour => {
				const [h, m] = hour.split(':').map(Number);
				if (h > currentHour) return true;
				if (h === currentHour && m > currentMinute) return true;
				return false;
			});
		}

		this.upcomingGarageAppointments
			.filter(a => a.appointment_date.slice(0, 10) === selectedDate)
			.forEach(a => {
				const time = a.appointment_time.slice(0, 5);
				const index = this.hours.indexOf(time);
				if (index !== -1) this.hours.splice(index, 1);
			});
	},
	onServiceChange() {
		if (!this.showGarages) return;
		if (!this.appointment.service_id) return;
		this.selectedGarage = null;
		this.appointment.garage_id = null;
		this.appointment.appointment_date = null;
		this.appointment.appointment_time = null;
		this.showDate = false;

		if (this.map) {
			this.map.setZoom(7);
			this.map.setCenter({ lat: 36.8065, lng: 10.1815 });
		}

		store.dispatch('garage-module/getGaragesByService', this.appointment.service_id)
		.then(() => {
				this.garagesByService = store.getters['garage-module/garageByService']
				this.showGarages = true
				this.$nextTick(() => {
				this.initMap()
				this.showGaragesOnMap()
			})
		})
	},
  },
  created() {
    store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
		const serviceIdFromRoute = this.$route.query.service_id;
		if (serviceIdFromRoute) {
			this.appointment.service_id = Number(serviceIdFromRoute);
		}
	})
	
	if(this.isLoggedIn){
		this.$store.dispatch('client-vehicle-module/getClientVehicles').then(() => {
			this.vehicles = store.getters['client-vehicle-module/clientVehicles']
			const vehicleIdFromRoute = this.$route.query.vehicle_id;
			if (vehicleIdFromRoute) {
				this.appointment.vehicle_id = Number(vehicleIdFromRoute);
			}
		})
	}
	else{
		const { type_id, brand_id, modele_id, service_id } = this.$route.query

		if (type_id && brand_id && modele_id) {
			Promise.all([
				store.dispatch('vehicle-type-module/getVehicleTypes'),
				store.dispatch('vehicle-brand-module/getVehicleBrands', { active: 1 }),
				store.dispatch('vehicle-modele-module/getVehicleModeles', { active: 1 })
			]).then(() => {

				const types = store.getters['vehicle-type-module/vehicle_types'].nodes
				const brands = store.getters['vehicle-brand-module/vehicle_brands'].nodes
				const modeles = store.getters['vehicle-modele-module/vehicle_modeles'].nodes

				const type = types.find(t => t.id == type_id)
				const brand = brands.find(b => b.id == brand_id)
				const modele = modeles.find(m => m.id == modele_id)

				this.guestVehicle = {
					type_id: type_id,
					brand_id: brand_id,
					modele_id: modele_id,
					vehicle_type: type,
					vehicle_brand: brand,
					vehicle_modele: modele
				}
			})
		}

		if (service_id) {
			this.appointment.service_id = Number(service_id)
		}
	}
  },
}
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
<style>
#add-appointment i, #add-appointment span, #add-appointment a {
    display: block;
}

#add-appointment .v-select{
	margin-bottom: 20px;
}

/* #add-appointment .vs__dropdown-toggle {
    border-radius: 0;
    background: #f3f7fa !important;
    height: 48px;
} */

.selected-garage {
  background-color: #d4edda;
  font-weight: bold;
}

.b-calendar-grid-help{
	display: none;
}

#add-appointment .garage-list{
  height: 425px;
  overflow-y: auto;
}

/* .flat-pickr{
	height: 48px;
    border-radius: 0;
    background: #f3f7fa !important;
} */
</style>
