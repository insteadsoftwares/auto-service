import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useVehicleModeleList() {
  // Use toast
  const toast = useToast()

  const refVehicleModeleListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'modele', label: 'modèle', sortable: true },
    { key: 'brand_id', label: 'Marque', sortable: true },
    { key: 'type_id', label: 'Type', sortable: true },
    { key: 'active', label: 'Actif', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalVehicleModeles = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refVehicleModeleListTable.value ? refVehicleModeleListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalVehicleModeles.value,
    }
  })

  const refetchData = () => {
    refVehicleModeleListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch Vehicle modeles
  const fetchVehicleModeles = (ctx, callback) => {
    store
      .dispatch('vehicle-modele-module/getVehicleModeles', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
		isClient: false, 
      })
      .then(() => {
        const { nodes, total } = store.getters['vehicle-modele-module/vehicle_modeles']
        callback(nodes)
        totalVehicleModeles.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des des Modèles de véhicules',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  return {
    fetchVehicleModeles,
    tableColumns,
    perPage,
    currentPage,
    totalVehicleModeles,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refVehicleModeleListTable,
    refetchData,
  }
}
