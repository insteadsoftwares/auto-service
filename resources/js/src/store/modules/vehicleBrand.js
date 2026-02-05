import axios from 'axios'

export default {
  namespaced: true,
  state: {
    vehicle_brands: {
      nodes: [],
      total: 0,
    },
  },
  getters: {
    vehicle_brands: state => state.vehicle_brands,
  },
  mutations: {
    SET_VEHICLE_BRANDS(state, val) {
      state.vehicle_brands.nodes = val.nodes
      state.vehicle_brands.total = val.total
    },
    ADD_VEHICLE_BRAND(state, val) {
      state.vehicle_brands.nodes.unshift(val)
      state.vehicle_brands.totalCount += 1
    },
  },
  actions: {
    async getVehicleBrands({ commit }) {
      try {
        const response = await axios.get('/api/vehicleBrands')
        commit('SET_VEHICLE_BRANDS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addVehicleBrand({ commit }, vehicleBrandData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addVehicleBrand', vehicleBrandData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_VEHICLE_BRAND', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editVehicleBrand({ commit }, vehicleBrandData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editVehicleBrand', vehicleBrandData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_VEHICLE_BRAND', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
