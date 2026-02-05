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

          <!-- first name -->
          <validation-provider
            #default="validationContext"
            name="first_name"
            rules="required"
          >
            <b-form-group
              label="Nom"
              label-for="first_name"
            >
              <b-form-input
                id="first_name"
                v-model="adminData.first_name"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          
		  <!-- last name -->
          <validation-provider
            #default="validationContext"
            name="last_name"
            rules="required"
          >
            <b-form-group
              label="Prénom"
              label-for="last_name"
            >
              <b-form-input
                id="last_name"
                v-model="adminData.last_name"
                :state="getValidationState(validationContext)"
                autofocus
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Email -->
          <validation-provider
            #default="validationContext"
            name="email"
            rules="required|email"
          >
            <b-form-group
              label="Email"
              label-for="email"
            >
              <b-form-input
                id="email"
                v-model="adminData.email"
                :state="getValidationState(validationContext)"
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Password -->
          <validation-provider
            #default="validationContext"
            name="password"
            :rules="isEditing ? 'password' : 'required|password'"
          >
            <b-form-group
              label="Mot de passe"
              label-for="password"
            >
              <b-input-group
                class="input-group-merge"
                :class="validationContext.errors[0] ? 'is-invalid':null"
              >
                <b-form-input
                  id="password"
                  v-model="adminData.password"
                  class="form-control-merge"
                  :state="getValidationState(validationContext)"
                  :type="passwordFieldType"
                  trim
                  placeholder="Password"
                />
                <b-input-group-append is-text>
                  <feather-icon
                    class="cursor-pointer"
                    :icon="passwordToggleIcon"
                    @click="togglePasswordVisibility"
                  />
                </b-input-group-append>
              </b-input-group>

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Phone Number -->
          <validation-provider
            #default="validationContext"
            name="phone number"
			rules="required|phone"
          >
            <b-form-group
              label="Numéro de téléphone"
              label-for="phone number"
            >
              <b-form-input
                id="phone_number"
                v-model="adminData.phone_number"
                :state="getValidationState(validationContext)"
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Address -->
          <validation-provider
            #default="validationContext"
            name="address"
			rules="required"
          >
            <b-form-group
              label="Address"
              label-for="address"
            >
              <b-form-input
                id="address"
                v-model="adminData.address"
                :state="getValidationState(validationContext)"
                trim
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
import {
  BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BInputGroup, BInputGroupAppend,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref } from '@vue/composition-api'
import { required, email, password, phone } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import store from '@/store'

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BInputGroup,
    BInputGroupAppend,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  mixins: [togglePasswordVisibility],
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
      default: () => this.blankAdminData,
    },
  },
  data() {
    return {
      required,
      password,
      email,
      phone,
    }
  },
  computed: {
    isEditing() {
      return !!this.value
    },
    title() {
      return this.isEditing ? 'Modifier un technicien' : 'Ajouter un nouveau technicien'
    },
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  watch: {
    value(value) {
      if (value) {
        this.adminData = {
          ...value,
          __typename: undefined,
        }
      }
  	},
  },
  setup(props, { emit }) {
    const toast = useToast()
    const blankAdminData = {
      role: 'technician',
      first_name: '',
      last_name: '',
      email: '',
      password: null,
      phone_number: '',
      address: '',
      enabled: false,
    }
    const adminData = ref(JSON.parse(JSON.stringify(blankAdminData)))
    const resetAdminData = () => {
      adminData.value = JSON.parse(JSON.stringify(blankAdminData))
    }
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetAdminData)

    const onSubmit = () => {
      let action
      // TODO: change to isEditing
      if (props.value) action = store.dispatch('user-module/editTechnician', adminData.value)
      else action = store.dispatch('user-module/addTechnician', adminData.value)

      action.then(() => {
        toast({
          component: ToastificationContent,
          props: {
            // TODO: change to isEditing
            title: `Le technician a été ${props.value ? 'modifié' : 'ajouté'} avec succès!`,
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
      blankAdminData,
      adminData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      handleSidebarHidden,
      handleCancelClick,
    }
  },
  methods: {
    // getEmailErrorMessage(error) {
    //   if (error == 'The Email field is required') return "Le Champ email est requis"
    //   if (error == 'The Email field must be a valid email') return "Le champ Email doit être un email valide"
    //   else return error
    // },
    // getPasswordErrorMessage(error) {
    //   if (error == 'The Password field is required') return "Le champ mot de passe est requise"
    //   if (error == 'Your password must contain at least one uppercase, one lowercase, one special character and one digit') return "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un caractère spécial et un chiffre"
    //   else return error
    // },
    // getPhoneNumberErrorMessage(error) {
    //   if (error == 'The phone number field is required') return "Le Champ numéro de téléphone est requis"
    //   if (error == 'The phone number field format is invalid') return "Le format du champ de numéro de téléphone n’est pas valide"
    //   else return error
    // }
  },
}
</script>
