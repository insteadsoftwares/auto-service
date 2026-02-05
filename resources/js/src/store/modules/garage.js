import axios from 'axios'

export default {
  namespaced: true,
  state: {
    garages: {
      nodes: [],
      total: 0,
    },
	garageByTechnician: null,
	garageByService: [],
  },
  getters: {
    garages: state => state.garages,
    garageByTechnician: state => state.garageByTechnician,
    garageByService: state => state.garageByService,
  },
  mutations: {
    SET_GARAGES(state, val) {
      state.garages.nodes = val.nodes
      state.garages.total = val.total
    },
    ADD_GARAGE(state, val) {
      state.garages.nodes.unshift(val)
      state.garages.totalCount += 1
    },
    SET_GARAGE_BY_TECHNICIAN(state, val) {
      state.garageByTechnician = val
    },
    SET_GARARES_BY_SERVICE(state, val) {
      state.garageByService = val
    },
  },
  actions: {
    async getGarages({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get('/api/garages', { params: queryParams, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_GARAGES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addGarage({ commit }, garageData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addGarage', garageData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_GARAGE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editGarage({ commit }, garageData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editGarage', garageData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_GARARE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async deleteGarage({ commit }, garageId) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.delete(`/api/deleteGarage/${garageId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_GARAGE', response.data)
      } finally {
        // commit('SET_GARAGE', false)
      }
    },
    async getGarageByTechnician({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/garageByTechnician', { params: queryParams, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_GARAGE_BY_TECHNICIAN', response.data)
      } catch (error) {
		throw error
	  }
    },
    async updateGarageInfo({ commit }, garageData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/updateGarageInfo', garageData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_GARARE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getGaragesByService({ commit }, serviceId) {
      try {
        const response = await axios.get(`/api/garagesByService/${serviceId}`)
        commit('SET_GARARES_BY_SERVICE', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
