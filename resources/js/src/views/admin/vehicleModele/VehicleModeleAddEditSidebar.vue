<template>
  <b-sidebar
    id="add-new-vehicle-modele-sidebar"
    :visible="isAddNewVehicleModeleSidebarActive"
    backdrop
    bg-variant="white"
    no-header
    right
    shadow
    sidebar-class="sidebar-lg"
    @change="(val) => $emit('update:is-add-new-vehicle-modele-sidebar-active', val)"
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

          <!-- modele -->
          <validation-provider
            #default="validationContext"
            name="modele"
            rules="required"
          >
            <b-form-group
              label="Modèle"
              label-for="modele"
            >
              <b-form-input
                id="name"
                v-model="vehicleModeleData.modele"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

		  	<!-- Brand Id -->
			<validation-provider #default="validationContext" name="brand_id" rules="required">
				<b-form-group :state="getValidationState(validationContext)" label="Marque" label-for="brand-id">
					<v-select v-model="vehicleModeleData.brand_id" :clearable="true"
						:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="vehicleBrands.nodes"
						:reduce="val => val.id" label="name" input-id="brand-id">
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

		  	<!-- Type Id -->
			<validation-provider #default="validationContext" name="type_id" rules="required">
				<b-form-group :state="getValidationState(validationContext)" label="Type" label-for="type-id">
					<v-select v-model="vehicleModeleData.type_id" :clearable="true"
						:dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="vehicleTypes.nodes"
						:reduce="val => val.id" label="type" input-id="type-id">
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

		  <!-- Active (Checkbox) -->
          <validation-provider
            #default="validationContext"
            name="active"
            rules="required"
          >
			<b-form-group
              :state="getValidationState(validationContext)"
              label="Actif"
              label-for="active"
            >
              <b-form-checkbox
                v-model="vehicleModeleData.active"
                class="custom-control-primary"
                name="check-button"
                switch
              >
                <span class="switch-icon-left">
                  <feather-icon icon="MinusIcon" />
                </span>
                <span class="switch-icon-right">
                  <feather-icon icon="PlusIcon" />
                </span>
              </b-form-checkbox>
              <b-form-invalid-feedback
                :state="getValidationState(validationContext)"
              >
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

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
import { BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BFormCheckbox } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref } from '@vue/composition-api'
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
    BFormCheckbox,
    BFormInvalidFeedback,
    BButton,
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewVehicleModeleSidebarActive',
    event: 'update:is-add-new-vehicle-modele-sidebar-active',
  },
  props: {
    isAddNewVehicleModeleSidebarActive: {
      type: Boolean,
      required: true,
    },
    value: {
      type: Object,
      default: () => this.blankVehicleModeleData,
    },
  },
  data() {
    return {
      required,
      vehicleBrands: [],
      vehicleTypes: [],
    }
  },
  computed: {
    isEditing() {
      return !!this.value
    },
    title() {
      return this.isEditing ? 'Modifier un modèle de véhicule' : 'Ajouter un nouveau modèle de véhicule'
    },
  },
  watch: {
    value(value) {
      if (value) {
        this.vehicleModeleData = {
		  ...value,
          __typename: undefined,
        }
      }
  	},
  },
  setup(props, { emit }) {
    const toast = useToast()
    const blankVehicleModeleData = {
      modele: '',
      brand_id: '',
      type_id: '',
      active: true,
    }
    const vehicleModeleData = ref(JSON.parse(JSON.stringify(blankVehicleModeleData)))
    const resetVehicleModeleData = () => {
      vehicleModeleData.value = JSON.parse(JSON.stringify(blankVehicleModeleData))
    }
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetVehicleModeleData)

    const onSubmit = () => {
      let action
      // TODO: change to isEditing
      if (props.value) action = store.dispatch('vehicle-modele-module/editVehicleModele', vehicleModeleData.value)
      else action = store.dispatch('vehicle-modele-module/addVehicleModele', vehicleModeleData.value)

      action.then(() => {
        toast({
          component: ToastificationContent,
          props: {
            // TODO: change to isEditing
            title: `Le modèle de véhicule a été ${props.value ? 'modifié' : 'ajouté'} avec succès!`,
            icon: 'AlertTriangleIcon',
            variant: 'success',
          },
        })
        emit('refetch-data')
        emit('update:is-add-new-vehicle-modele-sidebar-active', false)
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
      blankVehicleModeleData,
      vehicleModeleData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      handleSidebarHidden,
      handleCancelClick,
    }
  },
  methods: {
    //
  },
  created() {
	store.dispatch('vehicle-brand-module/getVehicleBrands', { active: 1 }).then(() => {
		this.vehicleBrands = store.getters['vehicle-brand-module/vehicle_brands']
	})

	store.dispatch('vehicle-type-module/getVehicleTypes').then(() => {
		this.vehicleTypes = store.getters['vehicle-type-module/vehicle_types']
	})
  },
}
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>