import axios from 'axios'

export default {
  namespaced: true,
  state: {
    currentAdmin: JSON.parse(localStorage.getItem('currentAdmin')) || null,
    currentUser: JSON.parse(localStorage.getItem('currentUser')) || null,
  },
  getters: {
    currentAdmin: state => state.currentAdmin,
    currentUser: state => state.currentUser,
  },
  mutations: {
    SET_CURRENT_ADMIN(state, val) {
      state.currentAdmin = val
    },
    CLEAR_CURRENT_ADMIN(state) {
      state.currentAdmin = null
    },
    SET_CURRENT_USER(state, val) {
      state.currentUser = val
    },
    CLEAR_CURRENT_USER(state) {
      state.currentUser = null
    },
  },
  actions: {
	checkUser({ commit }) {
      const user = localStorage.getItem('currentUser')
      if (user) {
        commit('SET_CURRENT_USER', JSON.parse(user))
      }
    },
	async loginAdmin({ commit }, loginData) {
		try {
			const response = await axios.post('/api/loginAdmin', loginData)
			localStorage.setItem('token', response.data.token)
			axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
			localStorage.setItem('currentAdmin', JSON.stringify(response.data.user))
			commit('SET_CURRENT_ADMIN', response.data.user)
			return response.data
		} catch (error) {
			throw error
		}
	},
	async login({ commit }, loginData) {
		try {
			const response = await axios.post('/api/login', loginData)
			localStorage.setItem('tokenUser', response.data.token)
			axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
			localStorage.setItem('currentUser', JSON.stringify(response.data.user))
			commit('SET_CURRENT_USER', response.data.user)
			return response.data
		} catch (error) {
			throw error
		}
	},
	async logoutAdmin({ commit }) {
		try {
			axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
			await axios.post('/api/logout')
			localStorage.removeItem('token')
			localStorage.removeItem('currentAdmin')
			axios.defaults.headers.common['Authorization'] = ''
			commit('CLEAR_CURRENT_ADMIN')
		} catch (error) {
			console.error('Erreur lors du logout :', error)
		}
    },
	async logout({ commit }) {
		try {
			axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('tokenUser')}`;
			await axios.post('/api/logout')
			localStorage.removeItem('tokenUser')
			localStorage.removeItem('currentUser')
			axios.defaults.headers.common['Authorization'] = ''
			commit('CLEAR_CURRENT_USER')
		} catch (error) {
			console.error('Erreur lors du logout :', error)
		}
    },
	async register({ commit }, registerData) {
		try {
			const response = await axios.post('/api/register', registerData)
			// localStorage.setItem('token', response.data.token)
			// axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
			// localStorage.setItem('currentUser', JSON.stringify(response.data.user))
			// commit('SET_CURRENT_USER', response.data.user)
			return response.data
		} catch (error) {
			throw error
		}
	},
	async registerWithGoogle({ commit }, googleData) {
		try {
			const response = await axios.post('/api/registerGoogle', googleData);
			
			// localStorage.setItem('token', response.data.token);
			// axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
			// localStorage.setItem('currentUser', JSON.stringify(response.data.user));
			// commit('SET_CURRENT_USER', response.data.user);
			return response.data;
		} catch (error) {
			throw error;
		}
	},
	async loginWithGoogle({ commit }, userData) {
		try {
			const response = await axios.post("/api/loginGoogle", userData);
			localStorage.setItem("tokenUser", response.data.token);
			axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.token}`;
			localStorage.setItem('currentUser', JSON.stringify(response.data.user))
			commit("SET_CURRENT_USER", response.data.user);
			return response.data;
		} catch (error) {
			throw error;
		}
	},
	async forgotPassword({ commit }, userData) {
		try {
			const response = await axios.post('/api/password/email', userData);
			return response.data;
		} catch (error) {
			throw error;
		}
	},
	async resetPassword({ commit }, userData) {
		try {
			const response = await axios.post('/api/password/reset', userData);
			return response.data;
		} catch (error) {
			throw error;
		}
	}
  },
}
