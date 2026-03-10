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
        ref="refReviewsListTable"
        class="position-relative"
        :items="fetchReviews"
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
			{{ data.item.client.first_name }} {{ data.item.client.last_name }}
		</template>

        <!-- Column: garage -->
	  	<template #cell(garage)="data">
			<span v-if="data.item.appointment.service">
				{{ data.item.appointment.garage.name }}
			</span>
			<span v-else>-</span>
		</template>

        <!-- Column: service -->
	  	<template #cell(service)="data">
			<span v-if="data.item.appointment.service">{{ data.item.appointment.service.name }}</span>
			<span v-else>Service supprimé</span>
		</template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <feather-icon
            :id="`review-row-${data.item.id}-edit-icon`"
            icon="TrashIcon"
            size="16"
            class="mr-1 cursor-pointer"
			@click="deleteRecord(data.item)"
          />
          <b-tooltip
            title="Modifier"
            :target="`review-row-${data.item.id}-edit-icon`"
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
              :total-rows="totalReviews"
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
import useReviewList from './useReviewList'
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
      fetchReviews,
      tableColumns,
      perPage,
      currentPage,
      totalReviews,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refReviewsListTable,
      refetchData,
    } = useReviewList()

    return {
      fetchReviews,
      tableColumns,
      perPage,
      currentPage,
      totalReviews,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refReviewsListTable,
      refetchData,
      toast,
    }
  },
  methods: {
	deleteRecord(selectedRecord) {
      if (selectedRecord.id !== 0) {
        this.$bvModal.msgBoxConfirm('Êtes-vous sûr de vouloir supprimer cet avis?', {
          title: 'Veuillez confirmer',
          centered: true,
          okVariant: 'danger',
          okTitle: 'Confirmer',
          cancelTitle: 'Annuler'
        })
          .then(value => {
            if (value) {
              store.dispatch('review-module/deleteReview', selectedRecord.id).then(() => {
                this.toast({
                  component: ToastificationContent,
                  props: {
                    title: "L'avis a été supprimé avec succès!",
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
