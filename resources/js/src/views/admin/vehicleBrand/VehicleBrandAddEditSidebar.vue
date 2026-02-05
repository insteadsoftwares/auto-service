<template>
  <b-sidebar
    id="add-new-vehicle-brand-sidebar"
    :visible="isAddNewVehicleBrandSidebarActive"
    backdrop
    bg-variant="white"
    no-header
    right
    shadow
    sidebar-class="sidebar-lg"
    @change="(val) => $emit('update:is-add-new-vehicle-brand-sidebar-active', val)"
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
            name="name"
            rules="required"
          >
            <b-form-group
              label="Nom"
              label-for="name"
            >
              <b-form-input
                id="name"
                v-model="vehicleBrandData.name"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
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
                v-model="vehicleBrandData.active"
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
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewVehicleBrandSidebarActive',
    event: 'update:is-add-new-vehicle-brand-sidebar-active',
  },
  props: {
    isAddNewVehicleBrandSidebarActive: {
      type: Boolean,
      required: true,
    },
    value: {
      type: Object,
      default: () => this.blankVehicleBrandData,
    },
  },
  data() {
    return {
      required,
    }
  },
  computed: {
    isEditing() {
      return !!this.value
    },
    title() {
      return this.isEditing ? 'Modifier une marque de véhicule' : 'Ajouter une nouvelle marque de véhicule'
    },
  },
  watch: {
    value(value) {
      if (value) {
        this.vehicleBrandData = {
		  ...value,
          __typename: undefined,
        }
      }
  	},
  },
  setup(props, { emit }) {
    const toast = useToast()
    const blankVehicleBrandData = {
      name: '',
      active: true,
    }
    const vehicleBrandData = ref(JSON.parse(JSON.stringify(blankVehicleBrandData)))
    const resetVehicleBrandData = () => {
      vehicleBrandData.value = JSON.parse(JSON.stringify(blankVehicleBrandData))
    }
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetVehicleBrandData)

    const onSubmit = () => {
      let action
      // TODO: change to isEditing
      if (props.value) action = store.dispatch('vehicle-brand-module/editVehicleBrand', vehicleBrandData.value)
      else action = store.dispatch('vehicle-brand-module/addVehicleBrand', vehicleBrandData.value)

      action.then(() => {
        toast({
          component: ToastificationContent,
          props: {
            // TODO: change to isEditing
            title: `La marque de véhicule a été ${props.value ? 'modifié' : 'ajouté'} avec succès!`,
            icon: 'AlertTriangleIcon',
            variant: 'success',
          },
        })
        emit('refetch-data')
        emit('update:is-add-new-vehicle-brand-sidebar-active', false)
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
      blankVehicleBrandData,
      vehicleBrandData,
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
}
</script>
