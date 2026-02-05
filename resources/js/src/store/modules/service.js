import axios from 'axios'

export default {
  namespaced: true,
  state: {
    services: {
      nodes: [],
      total: 0,
    },
  },
  getters: {
    services: state => state.services,
  },
  mutations: {
    SET_SERVICES(state, val) {
      state.services.nodes = val.nodes
      state.services.total = val.total
    },
    ADD_SERVICE(state, val) {
      state.services.nodes.unshift(val)
      state.services.totalCount += 1
    },
  },
  actions: {
    async getService({ commit }, queryParams) {
      try {
        const response = await axios.get('/api/services', { params: queryParams})
        commit('SET_SERVICES', response.data)
      } catch (error) {
		throw error
	  }
    },
    async addService({ commit }, serviceData) {
      const currentUserToken = localStorage.getItem('token')
      try {
		const formData = new FormData()
		formData.append('name', serviceData.name)
		formData.append('description', serviceData.description)
		formData.append('image', serviceData.image)
        const response = await axios.post('/api/addService', formData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_SERVICE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async editService({ commit }, serviceData) {
      const currentUserToken = localStorage.getItem('token')
      try {
		const formData = new FormData()
		formData.append('id', serviceData.id)
		formData.append('name', serviceData.name)
		formData.append('description', serviceData.description)
		if (serviceData.image) {
			formData.append('image', serviceData.image)
		}
        const response = await axios.post('/api/editService', formData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('EDIT_SERVICE', response.data)
      } catch (error) {
		throw error
	  }
    },
    async deleteService({ commit }, serviceId) {
      const currentUserToken = localStorage.getItem('token')
      try {
        const response = await axios.delete(`/api/deleteService/${serviceId}`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_SERVICE', response.data)
      } finally {
        // commit('SET_SERVICE', false)
      }
    },
  },
}
