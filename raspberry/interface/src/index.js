import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import routes from './utils/routes'

import Dashboard from './pages/Dashboard.vue'
//import 'element-ui/lib/theme-chalk/index.css';
//import 'element-ui/lib/theme-default/index.css';
//import 'element-theme-default';
//import styles from './css/styles.scss';

import Element from 'element-ui'



Vue.use(Element)
// Yay! Routes FTW.
Vue.use(VueRouter)
// I've used Vue resource because it was handy, you can use Axios, fetch APIs or any magic wand you want.
Vue.use(VueResource)
// Vue.use(Store) //Get your own vuex store from https://vuex.vuejs.org/en/
/*
Vue.http.interceptors.push((request, next)=> {
  if(request.params === undefined) {
    request.params = {}
  }
  request.params.someToken = 'some-token-you-might-want';
  next();
})
*/
const router = new VueRouter({
  routes: routes.routes
})

const App = new Vue({
  el:'#home',
  router,
  name: 'HomeDashboard',
  render: h => h(Dashboard)
})




