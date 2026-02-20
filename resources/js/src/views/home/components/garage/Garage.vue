<template>
  <div class="about-area section-padding" id="garage-details">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
          <div class="info-content-area">

            <!-- NAME -->
            <div class="editable-field section-title" @mouseenter="hoverName = true" @mouseleave="hoverName = false">
              <div v-if="!editName" class="editable-display">
                <h2>{{ garage.name }}</h2>
                <i class="las la-edit edit-icon" v-show="hoverName" @click="startEdit('name')"  style="color: #f35c27"/>
              </div>
              <div v-else class="editable-edit">
                <b-form-input
                  v-model="form.name"
                  size="lg"
                  ref="nameInput"
                />
                <div class="button-row mt-2">
				  <b-button class="edit-btn" @click="saveEdit('name')">Modifier</b-button>
                  <b-button class="cancel-btn" @click="cancelEdit('name')">Annuler</b-button>
                </div>
              </div>
            </div>

            <!-- ADRESSE -->
            <div class="editable-field mt-3" @mouseenter="hoverAddress = true" @mouseleave="hoverAddress = false">
              <div v-if="!editAddress" class="editable-display d-flex align-items-center">
                <strong>{{ garage.address }}</strong>
                <i class="las la-edit edit-icon" v-show="hoverAddress" @click="startEdit('address')"  style="color: #f35c27"/>
              </div>
              <div v-else class="edit-mode">
                <b-form-input
					type="text"
					v-model="form.address"
					ref="addressInput"
					class="form-control"
					placeholder="Modifier l'adresse"
				/>

				<div class="button-row mt-2">
					<b-button class="edit-btn" @click="saveEdit('address')">Modifier</b-button>
                  	<b-button class="cancel-btn" @click="cancelEdit('address')">Annuler</b-button>
				</div>
              </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="editable-field mt-3" @mouseenter="hoverDesc = true" @mouseleave="hoverDesc = false">
              <div v-if="!editDescription" class="editable-display position-relative">
                <p style="padding-right: 15px">{{ garage.description }}</p>
                <i class="las la-edit edit-icon desc-icon" v-show="hoverDesc" @click="startEdit('description')" style="color: #f35c27"/>
              </div>
              <div v-else class="editable-edit">
                <b-form-textarea
                  v-model="form.description"
                  rows="4"
                  @keyup.enter="saveEdit('description')"
                  @keyup.esc="cancelEdit('description')"
                  ref="descInput"
                />
                <div class="button-row mt-2">
                  <b-button class="edit-btn" @click="saveEdit('description')">Modifier</b-button>
                  <b-button class="cancel-btn" @click="cancelEdit('description')">Annuler</b-button>
                </div>
              </div>
            </div>

			<div class="about-feature-list" v-if="garage.garage_services.length > 0">
				<div class="toggle-header d-flex align-items-center justify-content-between togg" @click="servicesIsOpen = !servicesIsOpen" >
					<h6 class="mb-0"><strong>Nos Services</strong></h6>
					<i class="las" :class="servicesIsOpen ? 'la-angle-up' : 'la-angle-down'"></i>
				</div>
				<b-collapse v-model="servicesIsOpen">
					<ul>
						<li class="highlight" v-for="garageService in garage.garage_services" :key="garageService.id">
							<i class="las la-check-square"></i>{{ garageService.service.name }}
						</li>
					</ul>
				</b-collapse>
			</div>

			<div class="about-feature-list" v-if="garage.garage_specialties.length > 0">
				<div class="toggle-header d-flex align-items-center justify-content-between " @click="specialtiesIsOpen = !specialtiesIsOpen" >
					<h6 class="mb-0"><strong>Nos Spécialités</strong></h6>
					<i class="las" :class="specialtiesIsOpen ? 'la-angle-up' : 'la-angle-down'"></i>
				</div>
				<b-collapse v-model="specialtiesIsOpen">
					<ul>
						<li class="highlight" v-for="garageSpecialty in garage.garage_specialties" :key="garageSpecialty.id">
							<i class="las la-check-square"></i>
							<span v-if="garageSpecialty.vehicle_type">{{ garageSpecialty.vehicle_type.type }}</span>
							<span v-else>-</span> |
							<span v-if="garageSpecialty.vehicle_brand">{{ garageSpecialty.vehicle_brand.name }}</span>
							<span v-else>-</span> |
							<span v-if="garageSpecialty.vehicle_modele">{{ garageSpecialty.vehicle_modele.modele }}</span>
							<span v-else>-</span>
						</li>
					</ul>
				</b-collapse>
			</div>

			<div style="margin-top: 30px;" v-if="upcomingAppointments.length > 0">
				<div class="toggle-header d-flex align-items-center justify-content-between" @click="appointmentsIsOpen = !appointmentsIsOpen" >
					<h6 class="mb-0"><strong>Nos Rendez-vous</strong></h6>
					<i class="las" :class="appointmentsIsOpen ? 'la-angle-up' : 'la-angle-down'"></i>
				</div>
				<b-collapse v-model="appointmentsIsOpen">
					<b-row v-for="upcomingAppointment in upcomingAppointments" :key="upcomingAppointment.id" class="appointment-item mx-0">
						<b-col cols="12" md="6" class="mb-2">
							<strong>Client:</strong> {{ upcomingAppointment.client.first_name }} {{ upcomingAppointment.client.last_name }}
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Service:</strong> {{ upcomingAppointment.service.name }}
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Date:</strong> {{ formatDate(upcomingAppointment.appointment_date) }}
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Heure:</strong> {{ formatTime(upcomingAppointment.appointment_time) }}
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Type:</strong> {{ upcomingAppointment.vehicle.vehicle_type.type }}
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Matricule:</strong>
							<span v-if="upcomingAppointment.vehicle.registration_number">{{ upcomingAppointment.vehicle.registration_number }}</span>
							<span v-else>-</span>
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Année:</strong>
							<span v-if="upcomingAppointment.vehicle.year">{{ upcomingAppointment.vehicle.year }}</span>
							<span v-else>-</span>
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Marque:</strong>
							<span v-if="upcomingAppointment.vehicle.vehicle_brand">{{ upcomingAppointment.vehicle.vehicle_brand.name }}</span>
							<span v-else>-</span>
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<strong>Modèle:</strong>
							<span v-if="upcomingAppointment.vehicle.vehicle_modele">{{ upcomingAppointment.vehicle.vehicle_modele.modele }}</span>
							<span v-else>-</span>
						</b-col>
						<b-col cols="12" md="6" class="mb-2">
							<b-dropdown variant="link" id="appointment-status" v-if="upcomingAppointment.status == 'pending' || upcomingAppointment.status == 'confirmed'">
								<template #button-content>
									<strong>État:</strong> <span style="color: #f55b15 !important;"> {{ formatStatus(upcomingAppointment.status) }}</span>
								</template>
								<b-dropdown-item @click="updateAppointmentStatus('pending', upcomingAppointment.id)"> En attente </b-dropdown-item>
								<b-dropdown-item @click="updateAppointmentStatus('confirmed', upcomingAppointment.id)"> Confirmé </b-dropdown-item>
								<b-dropdown-item @click="updateAppointmentStatus('cancelled', upcomingAppointment.id)"> Annulé </b-dropdown-item>
								<b-dropdown-item @click="updateAppointmentStatus('completed', upcomingAppointment.id)"> Terminé </b-dropdown-item>
							</b-dropdown>	
							<span v-else>
								<strong>État:</strong> {{ formatStatus(upcomingAppointment.status) }}
							</span>					
						</b-col>
						<b-col cols="12" class="mb-2">
							<strong>description:</strong>
							<span v-if="upcomingAppointment.vehicle.description">{{ vehicle.description }}</span>
							<span v-else>-</span>
						</b-col>
					</b-row>
				</b-collapse>
			</div>

          </div>
        </div>

		<div class="col-lg-4">
			<QuestionSection/>
		</div>
      </div>
    </div>
  </div>
</template>

<script>
import { BFormInput, BFormTextarea, BButton, BCollapse, BRow, BCol, BDropdown, BDropdownItem } from 'bootstrap-vue'
import store from '@/store'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import QuestionSection from '../contact/QuestionSection.vue'

export default {
  components: {
    BFormInput,
    BFormTextarea,
    BButton,
    BCollapse,
	BRow, 
	BCol,
    AppTimeline,
    AppTimelineItem,
	BDropdown, 
	BDropdownItem,
	QuestionSection
  },
  props: {
    garage: {
		type: Object,
		default: null,
	},
  },
  data() {
    return {
      editName: false,
      editAddress: false,
      editDescription: false,
      hoverName: false,
      hoverAddress: false,
      hoverDesc: false,
      form: {
        name: '',
        address: '',
        description: ''
      },
	  servicesIsOpen: false,
      specialtiesIsOpen: false,
	  upcomingAppointments: [],
      appointmentsIsOpen: false,
    }
  },
  mounted() {
    this.resetForm()
  },
  methods: {
    resetForm() {
      this.form.name = this.garage.name
      this.form.address = this.garage.address
      this.form.description = this.garage.description
    },
    startEdit(field) {
	  this.form[field] = this.garage[field]
      this[`edit${this.capitalize(field)}`] = true
      this.$nextTick(() => {
        if (this.$refs[`${field}Input`]) {
          this.$refs[`${field}Input`].focus()
        }
      })
    },
    async saveEdit(field) {
		this.garage[field] = this.form[field]
		this[`edit${this.capitalize(field)}`] = false
		try {
			await this.$store.dispatch('garage-module/updateGarageInfo', this.garage)
			this.resetForm()
			this.$toast.success('Le garage a été modifié avec succès')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
    },
    cancelEdit(field) {
      this.form[field] = this.garage[field]
      this[`edit${this.capitalize(field)}`] = false
    },
    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    },
	formatDate(date) {
		if (!date) return '-'
		return new Intl.DateTimeFormat('fr-FR').format(new Date(date))
	},
	formatTime(time) {
		if (!time) return '-'
		return time.slice(0, 5)
	},
	formatStatus(status) {
		const statuses = {
			// pending: 'En attente',
			confirmed: 'Confirmé',
			cancelled: 'Annulé',
			// completed: 'Terminé',
		}
		return statuses[status] || status
	},
	async updateAppointmentStatus(status, appointment_id){
		try {
			const updatedAppointment = await this.$store.dispatch('appointment-module/editAppointmentStatus', {'status': status, 'appointment_id': appointment_id})
			const appointment = this.upcomingAppointments.find(a => a.id === appointment_id)
			if (appointment) appointment.status = store.getters['appointment-module/appointmentStatus'].status
			this.$toast.success('Le statut a été modifié avec succès')
		} catch (error) {
			this.$toast.error('Une erreur inattendue est survenue')
		}
	}
  },
  created() {
	this.$store.dispatch('appointment-module/getUpcomingTechnicianAppointments').then(() => {
		this.upcomingAppointments = store.getters['appointment-module/upcomingTechnicianAppointments']
	})
  }
}
</script>

<style scoped>
.editable-field {
  position: relative;
}

.editable-display {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.edit-icon {
  font-size: 20px;
  color: #007bff;
  cursor: pointer;
  margin-left: 15px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.editable-field:hover .edit-icon {
  opacity: 1;
}

.desc-icon {
  position: absolute;
  top: 0;
  right: 0;
}

.edit-btn {
    border: none;
    background: #f35c27;
    border-radius: 0;
}

.cancel-btn {
    border: none;
    border-radius: 0;
}

#garage-details .about-feature-list ul li {
    display: block;
}
</style>

<style>
#appointment-status .dropdown-toggle{
	padding: 0 !important;
    color: black !important;
    text-decoration: none !important;
}

#appointment-status .about-feature-list ul li{
	padding-left: 0 !important;
}

/* #appointment-status .dropdown-item{
	padding: 0 !important;
} */

#appointment-status .dropdown-menu{
	--bs-dropdown-border-color: none !important;
	--bs-dropdown-border-width: 0 !important;
	/* border: none !important; */
    box-shadow: rgb(208, 206, 206) 0px 3px 9px -2px !important;
}
</style>
