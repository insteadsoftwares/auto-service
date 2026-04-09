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
							v-if="$route.name === 'ClientSpacePage'"
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
						<b-form-group label="Véhicule" v-else>
							<b-form-input :value="getVehicleLabel()" disabled />
						</b-form-group>
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
									:get-option-label="serviceLabel"
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
								{{ garage.address }}<br>
								<div class="rating">
									<span
										v-for="star in 5"
										:key="star"
										:class="['star', getStarType(star, getGarageReviewRating(garage.reviews))]"
									>
										★
									</span> ( {{ garage.reviews.length }} Avis)
								</div>
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
						<flat-pickr 
							:key="appointment.garage_id"
							v-model="appointment.appointment_date" 
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
import { required } from 'vee-validate/dist/rules'
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
		const modele = vehicle.vehicle_modele ? vehicle.vehicle_modele.modele : '-'
		return `${vehicle.vehicle_type.type} | ${brand} | ${modele}`
	},
	selectGarage(garage) {
		this.selectedGarage = garage
		this.appointment.garage_id = garage.id
		this.setDisabledDatesAndWeekDays(garage)

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
			payload.guest_vehicle_model_id = this.guestVehicle.modele_id ? Number(this.guestVehicle.modele_id) : null
			payload.is_client = false
		}

		this.$store.dispatch('appointment-module/addAppointment', payload).then(() => {
			this.$toast.success('Rendez-vous réservé avec succès')
			if (payload.is_client) {
				this.$route.name == 'AddAppointmentPage' ? this.$router.push({ name: 'ClientSpacePage', query: { tab: 'appointments' } }) : this.$emit('appointment-added')
			} else {
				this.$router.push({ name: 'HomePage' })
			}
		})
		.catch(err => {
			console.error(err)
			this.$toast.error('Erreur lors de la réservation')
		})
	},
	updateDisabledTimes(selectedDates) {
		const date = selectedDates[0]
		if (!date || !this.selectedGarage) return

		const day = date.getDay()

		const workingDay = this.selectedGarage.garage_working_days.find(
			d => d.day_of_week === day
		)

		if (!workingDay || !workingDay.is_open) {
			this.hours = []
			return
		}

		let generatedHours = []

		workingDay.garage_working_hours.forEach(range => {
			let start = range.start_time.slice(0,5)
			let end = range.end_time.slice(0,5)

			let [sh, sm] = start.split(':').map(Number)
			let [eh, em] = end.split(':').map(Number)

			let current = new Date()
			current.setHours(sh, sm, 0)

			let endDate = new Date()
			endDate.setHours(eh, em, 0)

			while(current < endDate){
				const hour =
					String(current.getHours()).padStart(2,'0') +
					":" +
					String(current.getMinutes()).padStart(2,'0')

				generatedHours.push(hour)
				current.setHours(current.getHours() + 1)
			}
		})

		this.hours = generatedHours
		this.removeBookedHours()
	},
	removeBookedHours() {
		const selectedDate = this.appointment.appointment_date
		this.upcomingGarageAppointments
			.filter(a => a.appointment_date.slice(0,10) === selectedDate)
			.forEach(a => {

				const time = a.appointment_time.slice(0,5)
				const index = this.hours.indexOf(time)

				if(index !== -1){
					this.hours.splice(index,1)
				}

			})
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
	setDisabledDatesAndWeekDays(garage) {
		const garageLeaves = garage.garage_leaves || []
		const garageWorkingDays = garage.garage_working_days || []

		const disabledRanges = garageLeaves.map(leave => {
			const start = leave.start_date.substring(0, 10)
			const end = leave.end_date.substring(0, 10)

			const [sy, sm, sd] = start.split('-')
			const [ey, em, ed] = end.split('-')

			return {
				from: new Date(Number(sy), Number(sm) - 1, Number(sd)),
				to: new Date(Number(ey), Number(em) - 1, Number(ed)),
			}
		})

		const closedDays = garageWorkingDays
			.filter(day => !day.is_open)
			.map(day => day.day_of_week)

		const disableWeekDays = function(date) {
			return closedDays.includes(date.getDay())
		}

		this.dateConfig = {
			...this.dateConfig,
			disable: [
				...disabledRanges,
				disableWeekDays
			]
		}
	},
	getGarageReviewRating(reviews){
		if (!reviews || reviews.length === 0) {
			return 0
		}
		const total = reviews.reduce((sum, review) => {
			return sum + Number(review.rating)
		}, 0)
		const average = total / reviews.length
		return Math.round(average * 10) / 10
	},
	getStarType(starIndex, rating) {
		if (rating >= starIndex) {
			return 'full'
		} else if (rating >= starIndex - 0.5) {
			return 'half'
		} else {
			return 'empty'
		}
	},
	getVehicleLabel(){
		if (!this.appointment.vehicle_id || !this.vehicles.length) return '';

		const vehicle = this.vehicles.find(v => v.id === this.appointment.vehicle_id);
		if (!vehicle) return '';

		const brand = vehicle.vehicle_brand?.name || '-';
		const modele = vehicle.vehicle_modele?.modele || '-';
		const type = vehicle.vehicle_type?.type || '-';

		return `${type} | ${brand} | ${modele}`;
	},
	serviceLabel(service) {
		if (!service.duration) return '';
		const h = Math.floor(service.duration / 60);
		const m = service.duration % 60;

		let duration = '';
		if (h > 0) duration += h + ' h';
		if (m > 0) duration += (h > 0 ? ' ' : '') + m + ' min';
      	return `${service.name} (${duration})`
    }
  },
  created() {
    store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
	})
	
	if(this.isLoggedIn){
		const routeName = this.$route.name;
		this.$store.dispatch('client-vehicle-module/getClientVehicles').then(() => {
			this.vehicles = store.getters['client-vehicle-module/clientVehicles']
		})
		
		if(routeName === 'AddAppointmentPage'){
			const vehicleIdFromRoute = this.$route.query.vehicle_id;
			if (vehicleIdFromRoute) {
				this.appointment.vehicle_id = Number(vehicleIdFromRoute);
			}
		}
	}
	else{
		const { type_id, brand_id, modele_id, guest_name, guest_phone } = this.$route.query

		this.appointment.guest_name = guest_name
		this.appointment.guest_phone = guest_phone
		
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
			const modele = modele_id ? modeles.find(m => m.id == modele_id) : null

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

.rating{
  display: flex;
  align-items: center;
}

.rating i{
  color:#ff0000;
  font-size:18px;
}

.star {
  font-size: 18px;
  color: lightgray;
  display: inline-block;
  position: relative;
  width: 18px;
  text-align: center;
}

.star.full {
  color: #F35C27;
}

.star.half {
  color: lightgray;
}

.star.half::before {
  content: '★';
  color: #F35C27;
  position: absolute;
  left: 0;
  width: 50%;
  overflow: hidden;
  top: 0;
}
</style>
