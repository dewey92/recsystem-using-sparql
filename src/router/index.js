import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import FuncIndividuals from '@/components/FuncIndividuals'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Hello',
      component: Hello
    },
    {
      path: '/func-individuals',
      name: 'FuncIndividuals',
      component: FuncIndividuals
    }
  ]
})
