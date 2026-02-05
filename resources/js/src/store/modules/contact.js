import axios from 'axios'

export default {
  namespaced: true,
  state: {
	contact: null,
  },
  getters: {
    contact: state => state.contact,
  },
  mutations: {
    SET_CONTACT(state, val) {
      //
    },
  },
  actions: {
    async contact({ commit }, contactData) {
      try {
        const response = await axios.post('/api/contact', contactData)
        commit('SET_CONTACT', response.data)
      } catch (error) {
		throw error
	  }
    },
    async askQuestion({ commit }, contactData) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post('/api/askQuestion', contactData, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('SET_CONTACT', response.data)
      } catch (error) {
		throw error
	  }
    },
  },
}
