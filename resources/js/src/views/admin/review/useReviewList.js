import { ref, watch, computed } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store'

export default function useReviewList() {
  // Use toast
  const toast = useToast()

  const refReviewsListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'client_id', label: 'Client', sortable: true },
    { key: 'garage', label: 'Garage', sortable: true },
    { key: 'service', label: 'Service', sortable: true },
    { key: 'rating', label: 'rating', sortable: true },
    { key: 'comment', label: 'commentaire', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalReviews = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refReviewsListTable.value ? refReviewsListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalReviews.value,
    }
  })

  const refetchData = () => {
    refReviewsListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  // TODO: fetch review
  const fetchReviews = (ctx, callback) => {
    store
      .dispatch('review-module/getReviews', {
        searchQuery: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        active: null,
		isClient: false, 
      })
      .then(() => {
        const { nodes, total } = store.getters['review-module/reviews']
        callback(nodes)
        totalReviews.value = total
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
  }
}
