<template>
  <div class="about-area section-padding" id="add-appointment">
    <div class="container">
      	<h2>Prendre Rendez-vous</h2>
		<div class="contact-form">
			<b-row>
				<b-col class="col-lg-6 col-md-6 col-12">
					<b-form-group label="Véhicule">
						<v-select
							v-model="appointment.vehicle_id"
							:options="vehicles"
							:reduce="val => val.id"
							:get-option-label="vehicleLabel"
							placeholder="Sélectionner véhicule"
						/>
					</b-form-group>
				</b-col>
				<b-col class="col-lg-6 col-md-6 col-12">
					<b-form-group label="Service">
						<v-select
							v-model="appointment.service_id"
							:options="services"
							:reduce="val => val.id"
							label="name"
							placeholder="Sélectionner service"
							@input="onServiceUserChange()"
						/>
					</b-form-group>
				</b-col>
			</b-row>
		</div>

		<div>
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
		
		<div>
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

			<div class="text-center mt-3">
				<button class="main-btn" @click="updateAppointment" style="border: none;">
					Modifier Rendez-vous
				</button>
			</div>
		</div>
    </div>
  </div>
</template>

<script>
import { BFormInput, BFormGroup, BButton, BFormInvalidFeedback, BRow, BForm, BCol } from 'bootstrap-vue'
import store from '@/store'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import vSelect from 'vue-select'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

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
	  selectedGarage: null,
      upcomingGarageAppointments: [],
      services: [],
	  appointment: {
		service_id: null,
		vehicle_id: null,
		garage_id: null,
		appointment_date: null,
      	appointment_time: null,
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
	  activeInfoWindow: null
    }
  },
  props: {
	appointmentData: {
		type: Object,
		default: null,
	},
  },
  watch: {
	appointmentData: {
		immediate: true,
		handler(newVal) {
			if (newVal) {
				this.appointment = { ...newVal, 
					appointment_time: newVal.appointment_time ? newVal.appointment_time.slice(0, 5) : null, 
					appointment_date: newVal.appointment_date ? newVal.appointment_date.slice(0, 10) : null, 
				}
				console.log(this.appointment)
				this.loadGaragesByService(false)
				this.updateDisabledTimes()
			}
		}
	}
  },
  computed: {
	yearRules() {
		return `numeric|min_value:1900|max_value:${new Date().getFullYear()}`
	},
  },
  methods: {
	onServiceUserChange() {
		this.resetDateTime()
		this.resetMap()
		this.loadGaragesByService(true)
	},
	loadGaragesByService(){
		store.dispatch('garage-module/getGaragesByService', this.appointment.service_id ).then(() => {
			this.garagesByService = store.getters['garage-module/garageByService']
			if (this.appointment.garage_id) {
				const g = this.garagesByService.find(g => g.id === this.appointment.garage_id)
				if (g) {
					this.selectedGarage = g
					this.selectGarage(g)
				}
			}
		})
	},
	resetDateTime() {
		this.appointment.appointment_date = null
		this.appointment.appointment_time = null
		this.upcomingGarageAppointments = []
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
	geocodeAddress(garage) {
		this.closeInfoWindow()

		const geocoder = new google.maps.Geocoder()

		geocoder.geocode({ address: garage.address }, (results, status) => {
			if (status === 'OK') {
			const location = results[0].geometry.location

			this.map.setZoom(15)
			this.map.setCenter(location)

			const marker = new google.maps.Marker({
				map: this.map,
				position: location,
				title: garage.name,
			})

			// 🔥 stocker le marker
			this.markers.push(marker)

			const infoWindow = new google.maps.InfoWindow({
				content: `
				<strong>${garage.name}</strong><br/>
				${garage.address}
				`,
			})

			infoWindow.open(this.map, marker)
			this.activeInfoWindow = infoWindow
			}
		})
	},
	vehicleLabel(vehicle) {
		const brand = vehicle.vehicle_brand ? vehicle.vehicle_brand.name : '-'
		const modele = vehicle.vehicle_brand ? vehicle.vehicle_modele.modele : '-'
		const type = vehicle.vehicle_type ? vehicle.vehicle_type.type : '-'
		return `${type} | ${brand} | ${modele}`
	},
	selectGarage(garage) {
		this.selectedGarage = garage
		this.appointment.garage_id = garage.id
		this.geocodeAddress(garage)

		this.$store.dispatch('appointment-module/getUpcomingGarageAppointments', garage.id).then(() => {
			this.upcomingGarageAppointments =
			store.getters['appointment-module/upcomingGarageAppointments']
		})
	},
	updateAppointment() {
		console.log('updateAppointment')
		const appointment = { ...this.appointment, id: this.appointmentData.id }
		console.log(appointment)

		this.$store.dispatch('appointment-module/editAppointment', appointment)
		.then(() => {
			this.$toast.success('Rendez-vous modifié avec succès')
			this.$router.push({ name: 'AppointmentsPage' })
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
	waitForGoogleMaps() {
		const interval = setInterval(() => {
			if (window.google && window.google.maps) {
				clearInterval(interval)
				this.initMap()
			}
		}, 10)
	},
	resetMap() {
		if (!this.map) return
		this.markers.forEach(m => m.setMap(null))
		this.markers = []
		this.closeInfoWindow()
		this.map.setCenter({ lat: 36.8065, lng: 10.1815 })
		this.map.setZoom(7)
	},
	closeInfoWindow() {
		if (this.activeInfoWindow) {
			this.activeInfoWindow.close()
			this.activeInfoWindow = null
		}
	}
  },
  mounted() {
	this.waitForGoogleMaps()
  },
  created() {
    store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
	})
	
	this.$store.dispatch('client-vehicle-module/getClientVehicles').then(() => {
		this.vehicles = store.getters['client-vehicle-module/clientVehicles']
	})
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

#add-appointment .vs__dropdown-toggle {
    border-radius: 0;
    background: #f3f7fa !important;
    height: 48px;
}

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

.flat-pickr{
	height: 48px;
    border-radius: 0;
    background: #f3f7fa !important;
}
</style>
