import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useAppointmentList() {
  // Use toast
  const toast = useToast()

  const refAppointmentsListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'garage_id', label: 'Garage', sortable: true },
    { key: 'service_id', label: 'Service', sortable: true },
    { key: 'client_id', label: 'Client', sortable: true },
    { key: 'date', label: 'Date/Heure', sortable: true },
    { key: 'vehicle', label: 'Véhicule', sortable: true },
    { key: 'status', label: 'État', sortable: true },
  ]
  const perPage = ref(10)
  const totalAppointments = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refAppointmentsListTable.value ? refAppointmentsListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalAppointments.value,
    }
  })

  const refetchData = () => {
    refAppointmentsListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch Appointment
  const fetchAppointments = (ctx, callback) => {
    store
      .dispatch('appointment-module/getAllAppointments', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
      })
      .then(() => {
        const { nodes, total } = store.getters['appointment-module/allAppointments']
        callback(nodes)
        totalAppointments.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des avis',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

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
  }
}
