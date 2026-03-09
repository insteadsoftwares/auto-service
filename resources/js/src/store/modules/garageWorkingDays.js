import axios from 'axios'

export default {
  namespaced: true,
  state: {
    garageWorkingDays: null,
  },
  getters: {
    garageWorkingDays: state => state.garageWorkingDays,
  },
  mutations: {
    EDIT_GARAGE_WORKING_DAYS(state, val) {
      state.garageWorkingDays = val
    },
  },
  actions: {
    async editGarageWorkingDays({ commit }, queryParams) {
      const currentUserToken = localStorage.getItem('tokenUser')
      try {
        const response = await axios.post(`/api/editGarageWorkingDays`, queryParams, { headers: { Authorization: `Bearer ${currentUserToken}` } })
        commit('EDIT_GARAGE_WORKING_DAYS', response.data)
      } finally {
        // commit('EDIT_GARAGE_WORKING_DAYS', false)
      }
    },
  },
}