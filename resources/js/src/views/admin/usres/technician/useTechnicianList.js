import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useTechnicianList() {
  // Use toast
  const toast = useToast()

  const refAdminListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'first_name', label: 'Nom', sortable: true },
    { key: 'last_name', label: 'Prénom ', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'phone_number', label: 'Numéro de téléphone', sortable: true },
    { key: 'address', label: 'Adresse', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalAdmins = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refAdminListTable.value ? refAdminListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalAdmins.value,
    }
  })

  const refetchData = () => {
    refAdminListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch Technicians
  const fetchAdmins = (ctx, callback) => {
    store
      .dispatch('user-module/getTechnicians', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
      })
      .then(() => {
        const { nodes, total } = store.getters['user-module/technicians']
        callback(nodes)
        totalAdmins.value = total
      })
      .catch(e => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Erreur lors de la récupération de la liste des techniciens',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  return {
    fetchAdmins,
    tableColumns,
    perPage,
    currentPage,
    totalAdmins,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refAdminListTable,
    refetchData,
  }
}
