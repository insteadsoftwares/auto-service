import axios from 'axios'

export default {
  namespaced: true,
  state: {
    clientVehicles: []
  },
  getters: {
    clientVehicles: state => state.clientVehicles,
  },
  mutations: {
    SET_VEHICLES(state, val) {
      state.clientVehicles = val
    },
    ADD_CLIENT_VEHICLE(state, val) {
      state.clientVehicles.unshift(val)
    },
  },
  actions: {
    async getClientVehicles({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/clientVehicles', { params: queryParams, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_VEHICLES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addClientVehicle({ commit }, clientVehicleData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/addClientVehicle', clientVehicleData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_CLIENT_VEHICLE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editClientVehicle({ commit }, clientVehicleData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/editClientVehicle', clientVehicleData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_CLIENT_VEHICLE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async deleteClientVehicle({ commit }, clientVehicleId) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.delete(`/api/deleteClientVehicle/${clientVehicleId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_CLIENT_VEHICLE', response.data)
      } finally {
        // commit('SET_CLIENT_VEHICLE', false)
      }
    },
  },
}
