import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const initialState = {
  api: 'http://localhost:7000',
  userName: '',
  userPreferences: {
    price: 0,
    brand: ''
  },
  functionalReqs: [],
  funcIndividuals: [],
  productsFromFuncIndividuals: []
}

const mutations = {
  backToDefaultState (state) { state = initialState },
  setUserName (state, name) { state.userName = name },
  setUserPreferences (state, pref) { state.userPreferences = pref },
  setUserFunctionalReqs (state, reqs) { state.functionalReqs = reqs },
  setUserIndividuals (state, inds) { state.funcIndividuals = inds },
  setProductsFromFuncIndividuals (state, inds) {
    state.productsFromFuncIndividuals = inds.map(p => ({
      name: p,
      shown: false,
      clicked: false,
      details: {}
    }))
  }
}

const getters = {
  api: ({ api }) => api
}

export default new Vuex.Store({
  state: initialState,
  mutations,
  getters
})
