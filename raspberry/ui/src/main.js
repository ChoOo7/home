
window.$ = window.jQuery = require("jquery");
import Vue from 'vue'
//import App from './App.vue'
import Dashboard from './components/Dashboard.vue'

import VueResource from 'vue-resource'
Vue.use(VueResource)

import Element from 'element-ui'
Vue.use(Element);

Vue.use(require('vue-moment'));
Vue.config.productionTip = false

new Vue({
  render: h => h(Dashboard),
}).$mount('#home')

/*
const App = new Vue({
    el:'#home',
    router,
    name: 'HomeDashboard',
    render: h => h(Dashboard)
})



*/