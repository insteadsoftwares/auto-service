<template>
  <div>
    <GarageAddEditSidebar
      :is-add-new-garage-sidebar-active.sync="isAddNewGarageSidebarActive"
      :value.sync="activeRecord"
      @refetch-data="refetchData"
      @sidebarHidden="sidebarHidden"
    />
    <!-- Table Container Card -->
    <b-card
      no-body
      class="mb-0"
    >
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col
            cols="12"
            md="6"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
          >
            <label>Show</label>
            <v-select
              v-model="perPage"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="perPageOptions"
              :clearable="false"
              class="per-page-selector d-inline-block mx-50"
            />
            <label>entries</label>
          </b-col>

          <!-- Search -->
          <b-col
            cols="12"
            md="6"
          >
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Recherche..."
              />
              <b-button
                variant="primary"
                @click="isAddNewGarageSidebarActive = true; activeRecord = null"
              >
                <span class="text-nowrap">Ajouter un garage</span>
              </b-button>
            </div>
          </b-col>
        </b-row>

      </div>

      <b-table
        ref="refGarageListTable"
        class="position-relative"
        :items="fetchGarages"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="Aucun enregistrement correspondant trouvé"
        :sort-desc.sync="isSortDirDesc"
      >

        <!-- Column: active -->
	  	<template #cell(active)="data">
			<span :style="{ color: data.item.active ? 'green' : 'red', fontWeight: 'bold' }">
				{{ data.item.active ? 'Actif' : 'Inactif' }}
			</span>
		</template>
        
		<!-- Column: technician_id -->
	  	<template #cell(technician_id)="data">
			{{ data.item.technician.first_name }} {{ data.item.technician.last_name }}
		</template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <feather-icon
            :id="`garage-row-${data.item.id}-edit-icon`"
            icon="EditIcon"
            size="16"
            class="mr-1 cursor-pointer"
            @click="editRecord(data.item)"
          />
          <b-tooltip
            title="Modifier"
            :target="`garage-row-${data.item.id}-edit-icon`"
          />

		  <feather-icon
			:id="`garage-row-${data.item.id}-supprimer-icon`"
			icon="TrashIcon"
			size="16"
			class="mr-1 cursor-pointer"
			@click="deleteRecord(data.item)"
		  />
		  <b-tooltip
			title="Supprimer"
			:target="`garage-row-${data.item.id}-supprimer-icon`"
		  />

		  <span v-if="data.item.garage_specialties.length > 0">
			<feather-icon
				:id="`specialty-row-${data.item.id}-detail-icon`"
				icon="AwardIcon"
				size="16"
				class="mr-1 cursor-pointer"
				@click.stop="toggleSpecialties(data)"
			/>
			<b-tooltip
				title="Spécialités"
				:target="`specialty-row-${data.item.id}-detail-icon`"
			/>
          </span>

		  <span v-if="data.item.garage_services.length > 0">
			<feather-icon
				:id="`service-row-${data.item.id}-detail-icon`"
				icon="SettingsIcon"
				size="16"
				class="cursor-pointer"
				@click.stop="toggleServices(data)"
			/>
			<b-tooltip
				title="Services"
				:target="`service-row-${data.item.id}-detail-icon`"
			/>
          </span>
        </template>

		<template #row-details="data">
		  <div id="batch-list" v-if="data.item.showServices || data.item.showSpecialties">
			<b-table v-if="data.item.showSpecialties" :items="data.item.garage_specialties" :fields="garageSpecialtiesFields" responsive class="m-0 p-0">
				<template #cell(type)="data">
					<span v-if="data.item.vehicle_type">{{ data.item.vehicle_type.type }}</span>
					<span v-else>-</span>
				</template>

				<template #cell(brand)="data">
					<span v-if="data.item.vehicle_brand">{{ data.item.vehicle_brand.name }}</span>
					<span v-else>-</span>
				</template>

				<template #cell(modele)="data">
					<span v-if="data.item.vehicle_modele">{{ data.item.vehicle_modele.modele }}</span>
					<span v-else>-</span>
				</template>
			</b-table>

			<b-table v-if="data.item.showServices" :items="data.item.garage_services" :fields="servicesFields" responsive class="m-0 p-0">
				<template #cell(name)="data">
					{{ data.item.service.name }}
				</template>

				<template #cell(description)="data">
					{{ data.item.service.description }}
				</template>
			</b-table>
		  </div>
		</template>

      </b-table>
      <div class="mx-2 mb-2">
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-start"
          >
            <span class="text-muted">Showing {{ dataMeta.from }} to {{ dataMeta.to }} of {{ dataMeta.of }} entries</span>
          </b-col>
          <!-- Pagination -->
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
          >

            <b-pagination
              v-model="currentPage"
              :total-rows="totalGarages"
              :per-page="perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
              </template>			  
            </b-pagination>

          </b-col>

        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import { BCard, BRow, BCol, BFormInput, BButton, BTable, BPagination, BTooltip } from 'bootstrap-vue'
import vSelect from 'vue-select'
import { ref } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import useGarageList from './useGarageList'
import GarageAddEditSidebar from './GarageAddEditSidebar.vue'
import store from '@/store'

export default {
  components: {
    GarageAddEditSidebar,
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BPagination,
    BTooltip,
    vSelect,
  },
  data() {
	return {
		garageSpecialtiesFields: [
			{ key: 'type', label: 'Type de véhicule', sortable: true },
			{ key: 'brand', label: 'Marque de véhicule', sortable: true },
			{ key: 'modele', label: 'Modèle de véhicule', sortable: true },
		],
		servicesFields: [
			{ key: 'name', label: 'Nom', sortable: true },
			{ key: 'description', label: 'Description', sortable: true },
		],
	}
  },
  setup() {
    const toast = useToast()
    const isAddNewGarageSidebarActive = ref(false)
    const activeRecord = ref(null)

    const editRecord = selectedRecord => {
      activeRecord.value = selectedRecord
      isAddNewGarageSidebarActive.value = true
    }

    const {
      fetchGarages,
      tableColumns,
      perPage,
      currentPage,
      totalGarages,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refGarageListTable,
      refetchData,
    } = useGarageList()

    return {
      isAddNewGarageSidebarActive,
      activeRecord,
      fetchGarages,
      tableColumns,
      perPage,
      currentPage,
      totalGarages,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refGarageListTable,
      refetchData,
      editRecord,
      toast,
    }
  },
  methods: {
    deleteRecord(selectedRecord) {
      if (selectedRecord.id !== 0) {
        this.$bvModal.msgBoxConfirm('Êtes-vous sûr de vouloir supprimer ce garage?', {
          title: 'Veuillez confirmer',
          centered: true,
          okVariant: 'danger',
          okTitle: 'Confirmer',
          cancelTitle: 'Annuler'
        })
          .then(value => {
            if (value) {
              store.dispatch('garage-module/deleteGarage', selectedRecord.id).then(() => {
                this.toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Le garage a été supprimée avec succès!',
                    icon: 'AlertTriangleIcon',
                    variant: 'success',
                  },
                })
                this.refetchData()
              }).catch(() => {
                this.toast({
                  component: ToastificationContent,
                  props: {
                    title: 'An unexpected error occured! Please retry!',
                    icon: 'AlertTriangleIcon',
                    variant: 'danger',
                  },
                })
              })
            }
          })
          .catch(err => {
            console.log(err)
            // An error occurred
          })
      }
    },
    sidebarHidden() {
      this.activeRecord = null
    },
	toggleSpecialties(data) {
		if (data.detailsShowing && data.item.showSpecialties) {
			data.toggleDetails()
			data.item.showSpecialties = false
		} else {
			if (!data.detailsShowing) data.toggleDetails()
			data.item.showSpecialties = true
			data.item.showServices = false
		}
	},
	toggleServices(data) {
		if (data.detailsShowing && data.item.showServices) {
			data.toggleDetails()
			data.item.showServices = false
		} else {
			if (!data.detailsShowing) data.toggleDetails()
			data.item.showServices = true
			data.item.showSpecialties = false
		}
	},
  },
}
</script>

<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}
</style>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';

#batch-list .b-table{
	border: solid 1px #3b4253 !important;
}
</style>
