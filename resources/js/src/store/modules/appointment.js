import axios from 'axios'

export default {
  namespaced: true,
  state: {
    appointment: null,
	upcomingClientAppointments: [],
	expiredClientAppointments: [],
	upcomingTechnicianAppointments: [],
	expiredTechnicianAppointments: [],
	upcomingGarageAppointments: [],
	appointmentStatus: null,
	appointmentById: null,
  },
  getters: {
    appointment: state => state.appointment,
    upcomingClientAppointments: state => state.upcomingClientAppointments,
    expiredClientAppointments: state => state.expiredClientAppointments,
    upcomingTechnicianAppointments: state => state.upcomingTechnicianAppointments,
    expiredTechnicianAppointments: state => state.expiredTechnicianAppointments,
    upcomingGarageAppointments: state => state.upcomingGarageAppointments,
    appointmentStatus: state => state.appointmentStatus,
    appointmentById: state => state.appointmentById,
  },
  mutations: {
    SET_APPOINTMENT(state, val) {
      state.appointment = val
    },
    EDIT_APPOINTMENT_STATUS(state, val) {
      state.appointmentStatus = val
    },
    UPCOMING_CLIENT_APPOINTMENT(state, val) {
      state.upcomingClientAppointments = val
    },
    EXPIRED_CLIENT_APPOINTMENT(state, val) {
      state.expiredClientAppointments = val
    },
    UPCOMING_TECHNICIAN_APPOINTMENT(state, val) {
      state.upcomingTechnicianAppointments = val
    },
    EXPIRED_TECHNICIAN_APPOINTMENT(state, val) {
      state.expiredTechnicianAppointments = val
    },
    UPCOMING_GARAGE_APPOINTMENT(state, val) {
      state.upcomingGarageAppointments = val
    },
    EDIT_APPOINTMENt(state, val) {
      //
    },
    APPOINTMENT_BY_ID(state, val) {
      state.appointmentById = val
    },
  },
  actions: {
    async addAppointment({ commit }, appointmentData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/addAppointment', appointmentData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editAppointmentStatus({ commit }, statusData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/editAppointmentStatus', statusData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('EDIT_APPOINTMENT_STATUS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getUpcomingClientAppointments({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/upcomingClientAppointments', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('UPCOMING_CLIENT_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getExpiredClientAppointments({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/expiredClientAppointments', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('EXPIRED_CLIENT_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getUpcomingTechnicianAppointments({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/upcomingTechnicianAppointments', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('UPCOMING_TECHNICIAN_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getExpiredTechnicianAppointments({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/expiredTechnicianAppointments', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('EXPIRED_TECHNICIAN_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getUpcomingGarageAppointments({ commit }, garageId) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/upcomingGarageAppointments/${garageId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('UPCOMING_GARAGE_APPOINTMENT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editAppointment({ commit }, appointmentData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/editAppointment', appointmentData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('EDIT_APPOINTMENt', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getAppointmentById({ commit }, appointmentId) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/appointmentById/${appointmentId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('APPOINTMENT_BY_ID', response.data)
      } catch (error) {
		throw error
	  }
    },
	async cancelAppointment({ commit }, appointmentId) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/cancelAppointment/${appointmentId}`, null,{ headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_APPOINTMENT_STATUS', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
