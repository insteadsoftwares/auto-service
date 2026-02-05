import axios from 'axios'

export default {
  namespaced: true,
  state: {
    vehicle_types: {
      nodes: [],
      total: 0,
    },
  },
  getters: {
    vehicle_types: state => state.vehicle_types,
  },
  mutations: {
    SET_VEHICLE_TYPES(state, val) {
      state.vehicle_types.nodes = val.nodes
      state.vehicle_types.total = val.total
    },
    ADD_VEHICLE_TYPE(state, val) {
      state.vehicle_types.nodes.unshift(val)
      state.vehicle_types.totalCount += 1
    },
  },
  actions: {
    async getVehicleTypes({ commit }) {
      try {
        const response = await axios.get('/api/vehicleTypes')
        commit('SET_VEHICLE_TYPES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addVehicleType({ commit }, vehicleTypeData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addVehicleType', vehicleTypeData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_VEHICLE_TYPE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editVehicleType({ commit }, vehicleTypeData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editVehicleType', vehicleTypeData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_VEHICLE_TYPE', response.data)
      } catch (error) {
		throw error
	  }
    },
	async deleteVehicleType({ commit }, vehicleTypeId) {
	  const currentUserToken = localStorage.getItem('token')
	  try {
		const response = await axios.delete(`/api/deleteVehicleType/${vehicleTypeId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
		// commit('DELETE_SERVICE', response.data)
	  } finally {
		// commit('SET_SERVICE', false)
	  }
	},
  },
}
