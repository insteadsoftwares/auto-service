<template>
  <b-sidebar
    id="add-new-garage-sidebar"
    :visible="isAddNewGarageSidebarActive"
    backdrop
    bg-variant="white"
    no-header
    right
    shadow
    sidebar-class="sidebar-lg"
    @change="(val) => $emit('update:is-add-new-garage-sidebar-active', val)"
	@hidden="handleSidebarHidden()"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 class="mb-0">
          {{ title }}
        </h5>

        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="handleCancelClick(hide)"
        />

      </div>

      <!-- BODY -->
      <validation-observer
        ref="refFormObserver"
        #default="{ handleSubmit }"
      >
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >

          <!-- name -->
          <validation-provider
            #default="validationContext"
            name="nom"
            rules="required"
          >
            <b-form-group
              label="Nom"
              label-for="name"
            >
              <b-form-input
                id="name"
                v-model="garageData.name"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

		  <!-- Technician Id -->
			<validation-provider #default="validationContext" name="Technicien" rules="required">
				<b-form-group :state="getValidationState(validationContext)" label="Technicien" label-for="technician-id">
					<v-select v-model="garageData.technician_id" :clearable="true"
						:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="techniciansOptions"
						:reduce="val => val.id" label="label" input-id="technician-id">
						<template v-slot:no-options>
							<div class="no-options">
								Désolé, pas d’option de correspondance
							</div>
						</template>
					</v-select>
					<b-form-invalid-feedback :state="getValidationState(validationContext)">
					{{ validationContext.errors[0] }}
					</b-form-invalid-feedback>
				</b-form-group>
			</validation-provider>

          <!-- address -->
          <validation-provider
            #default="validationContext"
            name="adresse"
            rules="required"
          >
            <b-form-group
              label="Adresse"
              label-for="address"
            >
              <b-form-input
                id="address"
                v-model="garageData.address"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

		  <!-- description -->
          <validation-provider
            #default="validationContext"
            name="description"
            rules="required"
          >
            <b-form-group
              label="Description"
              label-for="description"
            >
              <b-form-textarea
                id="description"
                v-model="garageData.description"
                :state="getValidationState(validationContext)"
                autofocus
                rows="4"
				max-rows="8"
				trim
              />

              	<b-form-invalid-feedback>
					{{ validationContext.errors[0] }}
				</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

		  	<!-- service Id -->
			<b-form-group label="Service" label-for="service-id">
				<v-select
					v-model="garageData.service_ids" 
					:clearable="true" 
					multiple
					:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" 
					:options="services"
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

		  	<!-- Type Id -->
			<b-form-group label="Type de véhicule" label-for="type-id">
				<v-select v-model="garageData.type_ids" :clearable="true" multiple
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
			<b-form-group label="Marque de véhicule" label-for="brand-id">
				<v-select v-model="garageData.brand_ids" :clearable="true" multiple
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
				<v-select v-model="garageData.modele_ids" :clearable="true" multiple
					:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="filteredModeles"
					:reduce="val => val.id" label="modele" input-id="modele-id">
					<template v-slot:no-options>
						<div class="no-options">
							Désolé, pas d’option de correspondance
						</div>
					</template>
				</v-select>
			</b-form-group>

          <!-- Form Actions -->
          <div class="d-flex mt-2">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              class="mr-2"
              type="submit"
              variant="primary"
            >
              {{ isEditing ? 'Modifier' : 'Ajouter' }}
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              variant="outline-secondary"
              @click="handleCancelClick(hide)"
            >
              Annuler
            </b-button>
          </div>

        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import { BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BFormTextarea } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, getCurrentInstance } from '@vue/composition-api'
import { required } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'
import vSelect from 'vue-select'

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BFormTextarea,
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewGarageSidebarActive',
    event: 'update:is-add-new-garage-sidebar-active',
  },
  props: {
    isAddNewGarageSidebarActive: {
      type: Boolean,
      required: true,
    },
    value: {
      type: Object,
      default: () => this.blankGarageData,
    },
  },
  data() {
    return {
      required,
      techniciansWithoutGarage: [],
      services: [],
      vehicleBrands: [],
      vehicleTypes: [],
      vehicleModeles: [],
    }
  },
  computed: {
    isEditing() {
      return !!this.value
    },
    title() {
      return this.isEditing ? 'Modifier un garage' : 'Ajouter une nouveau garage'
    },
	filteredModeles() {
		const modeles = this.vehicleModeles || []
		return modeles.filter(modele => {
			const matchType = !Array.isArray(this.garageData.type_ids) || !this.garageData.type_ids.length || this.garageData.type_ids.includes(modele.type_id)
			const matchBrand = !Array.isArray(this.garageData.brand_ids) || !this.garageData.brand_ids.length || this.garageData.brand_ids.includes(modele.brand_id)
			return matchType && matchBrand
		})
	},
	techniciansOptions() {
		let options = this.techniciansWithoutGarage.map(tech => ({
			...tech,
			label: `${tech.first_name} ${tech.last_name}`
		}))
		if (this.isEditing && this.garageData.technician_id) {
			const selectedTechExists = options.some(opt => opt.id === this.garageData.technician_id)
			if (!selectedTechExists && this.value.technician) {
				options.push({
					...this.value.technician,
					label: `${this.value.technician.first_name} ${this.value.technician.last_name}`
				})
			}
		}

		return options
	}
  },
  watch: {
    value(value) {
		if (value) {
			this.garageData = {
				...value,
				service_ids: value.garage_services.map(service => service.service_id),
				type_ids: this.uniqueNonNull(value.garage_specialties.map(gs => gs.vehicle_type_id)),
				brand_ids: this.uniqueNonNull(value.garage_specialties.map(gs => gs.vehicle_brand_id)),
				modele_ids: this.uniqueNonNull(value.garage_specialties.map(gs => gs.vehicle_modele_id)),
				__typename: undefined,
			}
		}
	},
	// 'garageData.type_ids'() {
	// 	this.garageData.modele_ids = []
	// },
	// 'garageData.brand_ids'() {
	// 	this.garageData.modele_ids = []
	// },
  },
  setup(props, { emit }) {
	const { proxy } = getCurrentInstance()
    const toast = useToast()
    const blankGarageData = {
      name: '',
      address: '',
      description: '',
      technician_id: '',
      service_ids: [],
      type_ids: [],
      brand_ids: [],
      modele_ids: [],
    }
    const garageData = ref(JSON.parse(JSON.stringify(blankGarageData)))
    const resetGarageData = () => {
      garageData.value = JSON.parse(JSON.stringify(blankGarageData))
    }
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetGarageData)

    const onSubmit = () => {
      let action
      // TODO: change to isEditing
      if (props.value) action = store.dispatch('garage-module/editGarage', garageData.value)
      else action = store.dispatch('garage-module/addGarage', garageData.value)

      action.then(() => {
        toast({
          component: ToastificationContent,
          props: {
            // TODO: change to isEditing
            title: `Le garage a été ${props.value ? 'modifié' : 'ajouté'} avec succès!`,
            icon: 'AlertTriangleIcon',
            variant: 'success',
          },
        })
		proxy.getTechniciansWithoutGarage()
        emit('refetch-data')
        emit('update:is-add-new-garage-sidebar-active', false)
      }).catch(e => {
        if (e.response?.status === 422) {
          refFormObserver.value.setErrors(e.response.data.errors)
        } else {
          toast({
            component: ToastificationContent,
            props: {
              title: 'An unexpected error occured. Please retry.',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
        }
      })
    }

	const handleSidebarHidden = () => {
      resetForm();
      emit('sidebarHidden')
    };

    const handleCancelClick = (hide) => {
      resetForm();
      hide();
      emit('sidebarHidden')
    };

    return {
      blankGarageData,
      garageData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
      handleSidebarHidden,
      handleCancelClick,
    }
  },
  methods: {
    uniqueNonNull(array) {
		return [...new Set(array.filter(id => id !== null))];
	},
	getTechniciansWithoutGarage(){
		store.dispatch('user-module/getTechniciansWithoutGarage', { active: 1 }).then(() => {
			this.techniciansWithoutGarage = store.getters['user-module/technicians_without_garage']
		})
	}
  },
  created() {
	this.getTechniciansWithoutGarage()
	
	store.dispatch('service-module/getService').then(() => {
		this.services = store.getters['service-module/services'].nodes
	})
	
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
