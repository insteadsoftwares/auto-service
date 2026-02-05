<template>
  <div>
    <VehicleBrandAddEditSidebar
      :is-add-new-vehicle-brand-sidebar-active.sync="isAddNewVehicleBrandSidebarActive"
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
                @click="isAddNewVehicleBrandSidebarActive = true; activeRecord = null"
              >
                <span class="text-nowrap">Ajouter une marque de véhicule</span>
              </b-button>
            </div>
          </b-col>
        </b-row>

      </div>

      <b-table
        ref="refVehicleBrandListTable"
        class="position-relative"
        :items="fetchVehicleBrands"
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

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <feather-icon
            :id="`Vehicle-type-row-${data.item.id}-edit-icon`"
            icon="EditIcon"
            size="16"
            class="mr-1 cursor-pointer"
            @click="editRecord(data.item)"
          />
          <b-tooltip
            title="Modifier"
            :target="`Vehicle-type-row-${data.item.id}-edit-icon`"
          />
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
              :total-rows="totalVehicleBrands"
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
import useVehicleBrandList from './useVehicleBrandList'
import VehicleBrandAddEditSidebar from './VehicleBrandAddEditSidebar.vue'

export default {
  components: {
    VehicleBrandAddEditSidebar,
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
  setup() {
    const toast = useToast()
    const isAddNewVehicleBrandSidebarActive = ref(false)
    const activeRecord = ref(null)

    const editRecord = selectedRecord => {
      activeRecord.value = selectedRecord
      isAddNewVehicleBrandSidebarActive.value = true
    }

    const {
      fetchVehicleBrands,
      tableColumns,
      perPage,
      currentPage,
      totalVehicleBrands,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refVehicleBrandListTable,
      refetchData,
    } = useVehicleBrandList()

    return {
      isAddNewVehicleBrandSidebarActive,
      activeRecord,
      fetchVehicleBrands,
      tableColumns,
      perPage,
      currentPage,
      totalVehicleBrands,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refVehicleBrandListTable,
      refetchData,
      editRecord,
    }
  },
  methods: {
	sidebarHidden() {
      this.activeRecord = null
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
</style>
