import axios from 'axios'

export default {
  namespaced: true,
  state: {
    admins: {
      nodes: [],
      total: 0,
    },
    technicians: {
      nodes: [],
      total: 0,
    },
	technicians_without_garage: []
  },
  getters: {
    admins: state => state.admins,
    technicians: state => state.technicians,
    technicians_without_garage: state => state.technicians_without_garage,
  },
  mutations: {
    SET_ADMINS(state, val) {
      state.admins.nodes = val.nodes
      state.admins.total = val.total
    },
    ADD_ADMIN(state, val) {
      state.admins.nodes.unshift(val)
      state.admins.totalCount += 1
    },
    SET_TECHNICIAN(state, val) {
      state.technicians.nodes = val.nodes
      state.technicians.total = val.total
    },
    ADD_TECHNICIAN(state, val) {
      state.technicians.nodes.unshift(val)
      state.technicians.totalCount += 1
    },
    SET_TECHNICIANS_WITHOUT_GARAGE(state, val) {
		state.technicians_without_garage = val
	}
  },
  actions: {
    async getAdmins({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get('/api/admins', { params: queryParams, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_ADMINS', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addAdmin({ commit }, userData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addAdmin', userData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_ADMIN', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getTechnicians({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get('/api/technicians', { params: queryParams, headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_TECHNICIAN', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addTechnician({ commit }, userData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/addTechnician', userData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_TECHNICIAN', response.data)
      } catch (error) {
		throw error
	  }
    },
    async getTechniciansWithoutGarage({ commit }) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.get('/api/getTechniciansWithoutGarage', { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_TECHNICIANS_WITHOUT_GARAGE', response.data)
      } catch (error) {
		throw error
	  }
    },
	async editAdmin({ commit }, userData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editAdmin', userData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_USER', response.data)
      } catch (error) {
		throw error
	  }
    },
	async editTechnician({ commit }, userData) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.post('/api/editTechnician', userData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_USER', response.data)
      } catch (error) {
		throw error
	  }
    }, 
    async deleteAdmin({ commit }, userId) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.delete(`/api/deleteAdmin/${userId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_USER', response.data)
      } finally {
        // commit('SET_USER', false)
      }
    },
    async deleteTechnician({ commit }, userId) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.delete(`/api/deleteTechnician/${userId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_USER', response.data)
      } finally {
        // commit('SET_USER', false)
      }
    },
  },
}
