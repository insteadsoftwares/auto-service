import axios from 'axios'

export default {
  namespaced: true,
  state: {
    reviews: null,
    review: null,
  },
  getters: {
    reviews: state => state.reviews,
    review: state => state.review,
  },
  mutations: {
    ADD_REVIEW(state, val) {
      state.review = val
    },
    SET_REVIEWS(state, val) {
      state.reviews = val
    },
  },
  actions: {
    async addReview({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/addReview`, queryParams, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_REVIEW', response.data)
      } finally {
        // commit('ADD_REVIEW', false)
      }
    },
    async getReviews({ commit }, queryParams) {
      try {
        const response = await axios.get(`/api/getReviews`, { params: queryParams})
        commit('SET_REVIEWS', response.data)
      } finally {
        // commit('SET_REVIEWS', false)
      }
    },
	async deleteReview({ commit }, reviewId) {
	  const currentUserToken = localStorage.getItem('token')
	  try {
		const response = await axios.delete(`/api/deleteReview/${reviewId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
		// commit('DELETE_REVIEW', response.data)
	  } finally {
		// commit('DELETE_REVIEW', false)
	  }
	},
  },
}