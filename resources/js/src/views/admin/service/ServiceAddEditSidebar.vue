<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewUserSidebarActive"
    backdrop
    bg-variant="white"
    no-header
    right
    shadow
    sidebar-class="sidebar-lg"
    @change="(val) => $emit('update:is-add-new-user-sidebar-active', val)"
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
                v-model="serviceData.name"
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
                v-model="serviceData.description"
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

		  <!-- image -->
          <validation-provider
            #default="validationContext"
            name="image"
            :rules="isEditing ? '' : 'required'"
          >
            <b-form-group
              label="Image"
              label-for="image"
            >
				<div v-if="serviceData.currentImage" class="mb-2">
					<img :src="`/homePage/img/service/${serviceData.currentImage}`" alt="Image actuelle" class="img-thumbnail" width="150">
				</div>
			  <b-form-file
					id="image"
					v-model="serviceData.image"
                	:state="getValidationState(validationContext)"
					accept="image/*"
					placeholder="Choisir une image"
					browse-text="Parcourir"
				/>

              	<b-form-invalid-feedback>
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
import { BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BFormTextarea, BFormFile } from 'bootstrap-vue'
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
    BFormInvalidFeedback,
    BButton,
    BFormTextarea,
    BFormFile,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewUserSidebarActive',
    event: 'update:is-add-new-user-sidebar-active',
  },
  props: {
    isAddNewUserSidebarActive: {
      type: Boolean,
      required: true,
    },
    value: {
      type: Object,
      default: () => this.blankServiceData,
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
      return this.isEditing ? 'Modifier un service' : 'Ajouter un nouveau service'
    },
  },
  watch: {
    value(value) {
      if (value) {
        this.serviceData = {
		  id: value.id, 
          name: value.name,
		  description: value.description,
		  image: null,
		  currentImage: value.image,
        }
      }
  	},
  },
  setup(props, { emit }) {
    const toast = useToast()
    const blankServiceData = {
      name: '',
      description: '',
      image: null,
      currentImage: '',
    }
    const serviceData = ref(JSON.parse(JSON.stringify(blankServiceData)))
    const resetServiceData = () => {
      serviceData.value = JSON.parse(JSON.stringify(blankServiceData))
    }
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetServiceData)

    const onSubmit = () => {
      let action
      // TODO: change to isEditing
      if (props.value) action = store.dispatch('service-module/editService', serviceData.value)
      else action = store.dispatch('service-module/addService', serviceData.value)

      action.then(() => {
        toast({
          component: ToastificationContent,
          props: {
            // TODO: change to isEditing
            title: `Le service a été ${props.value ? 'modifié' : 'ajouté'} avec succès!`,
            icon: 'AlertTriangleIcon',
            variant: 'success',
          },
        })
        emit('refetch-data')
        emit('update:is-add-new-user-sidebar-active', false)
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
      blankServiceData,
      serviceData,
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
