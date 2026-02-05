import axios from 'axios'

export default {
  namespaced: true,
  state: {
	statistics: null,
	appointmentStatistics: null,
	sixBestGarages: [],
	threeBestServices: [],
	appointmentsStatus: null,
	garageStatistics: null,
	appointmentsEvolution: [],
	appointmentsCountByService: [],
	threeBestClients: [],
  },
  getters: {
    statistics: state => state.statistics,
    appointmentStatistics: state => state.appointmentStatistics,
    sixBestGarages: state => state.sixBestGarages,
    threeBestServices: state => state.threeBestServices,
    appointmentsStatus: state => state.appointmentsStatus,
    garageStatistics: state => state.garageStatistics,
    appointmentsEvolution: state => state.appointmentsEvolution,
    appointmentsCountByService: state => state.appointmentsCountByService,
    threeBestClients: state => state.threeBestClients,
  },
  mutations: {
    SET_STATISTICS(state, val) {
      state.statistics = val
    },
    SET_APPOINTMENT_STATISTICS(state, val) {
      state.appointmentStatistics = val
    },
    SET_SIX_BEST_GARAGES(state, val) {
      state.sixBestGarages = val
    },
    SET_THREE_BEST_SERVICES(state, val) {
      state.threeBestServices = val
    },
    SET_APPOINTMENT_STATUS(state, val) {
      state.appointmentsStatus = val
    },
    SET_GARAGE_STATISTICS(state, val) {
      state.garageStatistics = val
    },
    SET_APPOINTMENT_EVOLUTION(state, val) {
      state.appointmentsEvolution = val
    },
    SET_APPOINTMENT_CUNT_BY_SERVICE(state, val) {
      state.appointmentsCountByService = val
    },
    SET_THREE_BEST_CLIENT(state, val) {
      state.threeBestClients = val
    },
  },
  actions: {
    async getStatistics({ commit }) {
      try {
        const response = await axios.get(`/api/statistics`)
        commit('SET_STATISTICS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getAppointmentStatistics({ commit }) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get(`/api/appointmentStatistics`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_APPOINTMENT_STATISTICS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getSixBestGarages({ commit }, serviceData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get(`/api/sixBestGarages`, { params: serviceData, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_SIX_BEST_GARAGES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getThreeBestServices({ commit }) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get(`/api/threeBestServices`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_THREE_BEST_SERVICES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getAppointmentsStatus({ commit }) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get(`/api/appointmentsStatus`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_APPOINTMENT_STATUS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getGarageStatistics({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/garageStatistics`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_GARAGE_STATISTICS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getAppointmentsEvolutionByDate({ commit }, filterData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/appointmentsEvolutionByDate`, { params: filterData, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_APPOINTMENT_EVOLUTION', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getAppointmentsCountByService({ commit }, filterData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/appointmentsCountByService`, { params: filterData, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_APPOINTMENT_CUNT_BY_SERVICE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getThreeBestClients({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/threeBestClients`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_THREE_BEST_CLIENT', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
