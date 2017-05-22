import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
  api: 'http://localhost:6969',
  userName: '',
  functionalReqs: [],
  funcIndividuals: []
}

const mutations = {
  setUserName (state, name) { state.userName = name },
  setUserFunctionalReqs (state, reqs) { state.functionalReqs = reqs },
  setUserIndividuals (state, inds) { state.funcIndividuals = inds }
}

const getters = {
  api: ({ api }) => api
}

export default new Vuex.Store({
  state,
  mutations,
  getters
})
