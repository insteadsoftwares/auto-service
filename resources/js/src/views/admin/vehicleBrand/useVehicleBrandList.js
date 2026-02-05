import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useVehicleBrandList() {
  // Use toast
  const toast = useToast()

  const refVehicleBrandListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', label: 'Nom', sortable: true },
    { key: 'active', label: 'Actif', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalVehicleBrands = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refVehicleBrandListTable.value ? refVehicleBrandListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalVehicleBrands.value,
    }
  })

  const refetchData = () => {
    refVehicleBrandListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch Vehicle brands
  const fetchVehicleBrands = (ctx, callback) => {
    store
      .dispatch('vehicle-brand-module/getVehicleBrands', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        active: null,
		isClient: false, 
      })
      .then(() => {
        const { nodes, total } = store.getters['vehicle-brand-module/vehicle_brands']
        callback(nodes)
        totalVehicleBrands.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des des marques de véhicules',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  return {
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
  }
}
