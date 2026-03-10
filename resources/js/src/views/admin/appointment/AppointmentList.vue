<template>
  <div>
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
              
            </div>
          </b-col>
        </b-row>

      </div>

      <b-table
        ref="refAppointmentsListTable"
        class="position-relative"
        :items="fetchAppointments"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="Aucun enregistrement correspondant trouvé"
        :sort-desc.sync="isSortDirDesc"
      >

        <!-- Column: client_id -->
	  	<template #cell(client_id)="data">
			<span v-if="data.item.is_client">{{ data.item.client.first_name }} {{ data.item.client.last_name }}</span>
			<span v-else>{{ data.item.guest_name }}</span>
		</template>

        <!-- Column: garage_id -->
	  	<template #cell(garage_id)="data">
			<span v-if="data.item.garage">{{ data.item.garage.name }}</span>
			<span v-else>-</span>
		</template>

        <!-- Column: service_id -->
	  	<template #cell(service_id)="data">
			<span v-if="data.item.service">{{ data.item.service.name }}</span>
			<span v-else>Service supprimé</span>
		</template>

        <!-- Column: date -->
	  	<template #cell(date)="data">
			{{ formatDate(data.item.appointment_date) }}<br>
			{{ formatTime(data.item.appointment_time) }}
		</template>

        <!-- Column: status -->
	  	<template #cell(status)="data">
			<span :class="['status ', data.item.status]">
				{{ formatStatus(data.item.status) }}
			</span>
		</template>

        <!-- Column: vehicle -->
	  	<template #cell(vehicle)="data">
			<span v-if="data.item.is_client">
				<span v-if="data.item.vehicle">
					<span v-if="data.item.vehicle.vehicle_type">{{ data.item.vehicle.vehicle_type.type }}</span>
					<span v-else>-</span>
					|
					<span v-if="data.item.vehicle.vehicle_brand">{{ data.item.vehicle.vehicle_brand.name }}</span>
					<span v-else>-</span>
					|
					<span v-if="data.item.vehicle.vehicle_modele">{{ data.item.vehicle.vehicle_modele.modele }}</span>
					<span v-else>-</span>
				</span>
				<span v-else>
					-|-|-
				</span>
			</span>
			<span v-else>
				<span v-if="data.item.guest_vehicle_type">
					{{ data.item.guest_vehicle_type.type }}
				</span>
				<span v-else>-</span>
				|
				<span v-if="data.item.guest_vehicle_brand">
					{{ data.item.guest_vehicle_brand.name }}
				</span>
				<span v-else>-</span>
				|
				<span v-if="data.item.guest_vehicle_modele">
					{{ data.item.guest_vehicle_modele.modele }}
				</span>
				<span v-else>-</span>
			</span>
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
              :total-rows="totalAppointments"
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
import useAppointmentList from './useAppointmentList'
import store from '@/store'

export default {
  components: {
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

    const {
      fetchAppointments,
      tableColumns,
      perPage,
      currentPage,
      totalAppointments,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refAppointmentsListTable,
      refetchData,
    } = useAppointmentList()

    return {
      fetchAppointments,
      tableColumns,
      perPage,
      currentPage,
      totalAppointments,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refAppointmentsListTable,
      refetchData,
      toast,
    }
  },
  methods: {
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
			pending: 'En attente',
			confirmed: 'Confirmé',
			cancelled: 'Annulé',
			completed: 'Terminé',
		}
		return statuses[status] || status
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

<style scoped>
.status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  white-space: nowrap;
  font-weight: 500;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}
.status.pending {
  background: #fff3cd;
  color: #856404;
}
.status.confirmed {
  background: #d4edda;
  color: #155724;
}
.status.completed {
  background: #cff6fc;
  color: #0dcaf0;;
}
.status.cancelled {
  color: red;
  background: #fbe5e5;
}
</style>
