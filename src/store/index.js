import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
  api: 'http://localhost:7000',
  userName: '',
  functionalReqs: [],
  funcIndividuals: [],
  productsFromFuncIndividuals: []
}

const mutations = {
  setUserName (state, name) { state.userName = name },
  setUserFunctionalReqs (state, reqs) { state.functionalReqs = reqs },
  setUserIndividuals (state, inds) { state.funcIndividuals = inds },
  setProductsFromFuncIndividuals (state, inds) { state.productsFromFuncIndividuals = inds }
}

const getters = {
  api: ({ api }) => api
}

export default new Vuex.Store({
  state,
  mutations,
  getters
})
