import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
  api: 'http://localhost:6969',
  name: '',
  functionalReqs: []
}

const mutations = {
  setUserName (state, name) { state.name = name },
  setUserFunctionalReqs (state, reqs) { state.functionalReqs = reqs }
}

const getters = {
  api: ({ api }) => api
}

export default new Vuex.Store({
  state,
  mutations,
  getters
})
