import axios from 'axios'

export default {
  namespaced: true,
  state: {
	clientNotifications: [],
	technicianNotifications: [],
  },
  getters: {
    clientNotifications: state => state.clientNotifications,
    technicianNotifications: state => state.technicianNotifications,
  },
  mutations: {
    SET_CLIENT_NOTIFICATIONS(state, val) {
      state.clientNotifications = val
    },
    SET_TECHNICIAN_NOTIFICATIONS(state, val) {
      state.technicianNotifications = val
    },
  },
  actions: {
    async getClientNotifications({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/clientNotifications', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_CLIENT_NOTIFICATIONS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getTechnicianNotifications({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get('/api/technicianNotifications', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_TECHNICIAN_NOTIFICATIONS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async markAsRead({ commit }, id) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/notification/markAsRead/${id}`, null,{ headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('SET_TECHNICIAN_NOTIFICATIONS', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
