import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useGarageList() {
  // Use toast
  const toast = useToast()

  const refGarageListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', label: 'Nom', sortable: true },
    { key: 'address', label: 'Adresse', sortable: true },
    { key: 'description', label: 'Description', sortable: true },
    { key: 'technician_id', label: 'Technicien', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalGarages = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refGarageListTable.value ? refGarageListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalGarages.value,
    }
  })

  const refetchData = () => {
    refGarageListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch garage
  const fetchGarages = (ctx, callback) => {
    store
      .dispatch('garage-module/getGarages', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
      })
      .then(() => {
        const { nodes, total } = store.getters['garage-module/garages']
		const garagesWithFlags = nodes.map(garage => ({
			...garage,
			showServices: false,
			showSpecialties: false,
		}))
        callback(garagesWithFlags)
        totalGarages.value = total
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
  }
}
