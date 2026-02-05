import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useServiceList() {
  // Use toast
  const toast = useToast()

  const refServiceListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', label: 'Service', sortable: true },
    { key: 'description', label: 'Description ', sortable: true },
    { key: 'image', label: 'Image', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalServices = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refServiceListTable.value ? refServiceListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalServices.value,
    }
  })

  const refetchData = () => {
    refServiceListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch services
  const fetchServices = (ctx, callback) => {
    store
      .dispatch('service-module/getService', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
      })
      .then(() => {
        const { nodes, total } = store.getters['service-module/services']
        callback(nodes)
        totalServices.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des services',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  return {
    fetchServices,
    tableColumns,
    perPage,
    currentPage,
    totalServices,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refServiceListTable,
    refetchData,
  }
}
