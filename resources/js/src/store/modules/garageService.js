import axios from 'axios'

export default {
  namespaced: true,
  state: {
    //
  },
  getters: {
    //
  },
  mutations: {
    //
  },
  actions: {
    async deleteGarageService({ commit }, serviceId) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.delete(`/api/deleteGarageService/${serviceId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_SERVICE', response.data)
      } finally {
        // commit('SET_SERVICE', false)
      }
    },
    async addGarageServices({ commit }, serviceIds) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/addGarageServices`, serviceIds, { headers: { Authorization: `Bearer ${currentUserToken}` } })
		return response.data 
        // commit('ADD_SERVICE', response.data)
      } finally {
        // commit('ADD_SERVICE', false)
      }
    },
  },
}