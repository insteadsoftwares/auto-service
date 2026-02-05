import axios from 'axios'

export default {
  namespaced: true,
  state: {
    vehicle_modeles: {
      nodes: [],
      total: 0,
    },
  },
  getters: {
    vehicle_modeles: state => state.vehicle_modeles,
  },
  mutations: {
    SET_VEHICLE_MODELES(state, val) {
      state.vehicle_modeles.nodes = val.nodes
      state.vehicle_modeles.total = val.total
    },
    ADD_VEHICLE_MODELE(state, val) {
      state.vehicle_modeles.nodes.unshift(val)
      state.vehicle_modeles.totalCount += 1
    },
  },
  actions: {
    async getVehicleModeles({ commit }) {
      try {
        const response = await axios.get('/api/vehicleModeles')
        commit('SET_VEHICLE_MODELES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addVehicleModele({ commit }, vehicleModeleData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addVehicleModele', vehicleModeleData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_VEHICLE_MODELE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editVehicleModele({ commit }, vehicleBrandData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editVehicleModele', vehicleBrandData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_VEHICLE_MODELE', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
