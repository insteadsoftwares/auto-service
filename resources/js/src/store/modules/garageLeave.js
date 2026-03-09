import axios from 'axios'

export default {
  namespaced: true,
  state: {
    addLeaves: null,
    garageLeaves: null,
  },
  getters: {
    addLeaves: state => state.addLeaves,
    garageLeaves: state => state.garageLeaves,
  },
  mutations: {
    ADD_GARAGE_LEAVES(state, val) {
      state.addLeaves = val
    },
    GARAGE_LEAVES(state, val) {
      state.garageLeaves = val
    },
  },
  actions: {
    async addGarageLeaves({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/addGarageLeaves`, queryParams, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('ADD_GARAGE_LEAVES', response.data)
      } finally {
        // commit('ADD_GARAGE_LEAVES', false)
      }
    },
    async deleteGarageLeave({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/deleteGarageLeave`, queryParams, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        // commit('DELETE_GARAGE_LEAVE', response.data)
      } finally {
        // commit('SET_SERVICE', false)
      }
    },
    async getGarageLeaves({ commit }) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.get(`/api/garageLeaves`, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('GARAGE_LEAVES', response.data)
      } finally {
        // commit('SET_SERVICE', false)
      }
    },
  },
}