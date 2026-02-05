<template>
  <div id="garage-details">
	<h3>Informations générales du garage</h3>
	<div v-if="garage">
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
    }
  },
  props: {
    garage: {
		type: Object,
		default: null,
	},
  },
  mounted() {
    this.resetForm()
  },
  methods: {
    resetForm() {
	  if (!this.garage) return
    //   this.form.name = this.garage.name
    //   this.form.address = this.garage.address
    //   this.form.description = this.garage.description
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
