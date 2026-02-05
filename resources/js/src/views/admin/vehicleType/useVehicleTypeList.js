import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useVehicleTypeList() {
  // Use toast
  const toast = useToast()

  const refVehicleTypeListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'type', label: 'Type', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalVehicleTypes = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refVehicleTypeListTable.value ? refVehicleTypeListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalVehicleTypes.value,
    }
  })

  const refetchData = () => {
    refVehicleTypeListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch Vehicle type
  const fetchVehicleTypes = (ctx, callback) => {
    store
      .dispatch('vehicle-type-module/getVehicleTypes', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
		isClient: false,
      })
      .then(() => {
        const { nodes, total } = store.getters['vehicle-type-module/vehicle_types']
        callback(nodes)
        totalVehicleTypes.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des des types de véhicules',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  return {
    fetchVehicleTypes,
    tableColumns,
    perPage,
    currentPage,
    totalVehicleTypes,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refVehicleTypeListTable,
    refetchData,
  }
}
